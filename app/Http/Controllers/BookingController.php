<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Models\Room;
use App\Models\Asset;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Models\Log;

class BookingController extends Controller
{
    // Hapus parameter (Room $room) agar form bisa diakses tanpa spesifik ruangan
    public function create(Request $request)
    {
        $rooms = Room::where('is_active', 1)->get();
        $assets = Asset::where('stock_available', '>', 0)->get();

        // Ambil ID ruangan jika dipassing dari URL (opsional)
        $selectedRoom = $request->get('room_id');

        return view('bookings.create', compact('rooms', 'assets', 'selectedRoom'));
    }

    // Ubah parameter (Request $request, Room $room) menjadi (Request $request) saja
    public function store(Request $request)
    {
        // 1. Validasi Input Dasar
        $request->validate([
            'room_id' => 'nullable|exists:rooms,id',
            'purpose' => 'required|string|max:255',
            'attendees' => 'required|integer|min:1',
            'start_time' => 'required|date|after_or_equal:today',
            'end_time' => 'required|date|after:start_time',
            'asset_ids' => 'nullable|array', // Array id aset yang dipilih
            'asset_ids.*' => 'exists:assets,id',
            'quantities' => 'nullable|array', // Array jumlah aset yang dipinjam
            'sop_agreement' => 'accepted'
        ]);
        // === 1.5 VALIDASI SOP DIGITAL PAROKI ===
        $start = \Carbon\Carbon::parse($request->start_time);
        $end = \Carbon\Carbon::parse($request->end_time);
        $now = \Carbon\Carbon::now();

        // Aturan 1: Minimal H-3 (3 hari sebelum acara)
        if ($start->copy()->startOfDay()->lt($now->copy()->addDays(3)->startOfDay())) {
            return back()->withInput()->withErrors(['start_time' => 'Sesuai SOP, peminjaman minimal dilakukan 3 hari sebelum acara.']);
        }

        // Aturan 2: Maksimal 3 Bulan ke depan
        if ($start->copy()->startOfDay()->gt($now->copy()->addMonths(3)->endOfDay())) {
            return back()->withInput()->withErrors(['start_time' => 'Peminjaman maksimal hanya dapat dilakukan untuk 3 bulan ke depan.']);
        }

        // Aturan 3: Durasi maksimal 3 jam per slot
        if ($start->diffInMinutes($end) > 180) { // 180 menit = 3 jam
            return back()->withInput()->withErrors(['end_time' => 'Sesuai SOP, durasi maksimal peminjaman adalah 3 jam per slot untuk memberi kesempatan pada kelompok lain.']);
        }
        // === SELESAI VALIDASI SOP ===
        // Proteksi: User harus memilih minimal ruangan atau aset
        if (empty($request->room_id) && empty($request->asset_ids)) {
            return back()->withInput()->withErrors(['general' => 'Anda harus memilih minimal 1 ruangan atau 1 aset untuk dipinjam.']);
        }

        // 2. LOGIKA DETEKSI KONFLIK RUANGAN (Hanya jika ruangan dipilih)
        if ($request->room_id) {
            $conflict = Booking::where('room_id', $request->room_id)
                ->whereIn('status', ['pending', 'approved'])
                ->where(function ($query) use ($request) {
                    $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                        ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                        ->orWhere(function ($q) use ($request) {
                            $q->where('start_time', '<=', $request->start_time)
                                ->where('end_time', '>=', $request->end_time);
                        });
                })->exists();

            if ($conflict) {
                return back()->withInput()->withErrors([
                    'start_time' => 'Maaf, ruangan sudah terpesan pada jam tersebut. Silakan geser waktu kegiatan Anda.'
                ]);
            }
        }

        // 3. Simpan Data Booking (Ruangan bisa null)
        $booking = Booking::create([
            'user_id' => auth()->id(),
            'room_id' => $request->room_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'purpose' => $request->purpose,
            'attendees' => $request->attendees,
            'status' => 'pending',
        ]);

        // 4. Simpan Peminjaman Aset ke Tabel Pivot `asset_booking`
        if ($request->has('asset_ids')) {
            $assetsToSync = [];
            foreach ($request->asset_ids as $index => $assetId) {
                $quantity = $request->quantities[$index] ?? 1;

                // Pastikan quantity valid
                if ($quantity > 0) {
                    $assetsToSync[$assetId] = ['quantity' => $quantity];
                }
            }
            // Attach ke database (relasi many-to-many)
            if (!empty($assetsToSync)) {
                $booking->assets()->sync($assetsToSync);
            }
        }

        return redirect()->route('dashboard')->with('success', 'Pengajuan jadwal/aset berhasil dikirim! Silakan tunggu konfirmasi Sekretariat.');
    }

public function approve(Request $request, $id)
    {
        $booking = Booking::with(['user', 'room', 'assets'])->findOrFail($id);

        // 1. CEK STOK SEBELUM APPROVE
        foreach ($booking->assets as $asset) {
            if ($asset->stock_available < $asset->pivot->quantity) {
                return redirect()->back()->with('error', 'Gagal Disetujui: Stok aset "' . $asset->asset_name . '" tidak mencukupi untuk dipinjam saat ini.');
            }
        }

        // 2. KURANGI STOK ASET JIKA AMAN
        foreach ($booking->assets as $asset) {
            $asset->decrement('stock_available', $asset->pivot->quantity);
        }

        $booking->update([
            'status' => 'approved',
            'admin_note' => $request->admin_note
        ]);

        Log::create([
            'booking_id' => $booking->id,
            'actor_id' => auth()->id(),
            'action' => 'approved',
        ]);

        // --- MULAI SCRIPT FONNTE API WA ---
        if ($booking->user->phone_number) {
            $tanggal = Carbon::parse($booking->start_time)->translatedFormat('l, d F Y');
            $jam = Carbon::parse($booking->start_time)->format('H:i') . ' - ' . Carbon::parse($booking->end_time)->format('H:i') . ' WIB';
            $namaRuangan = $booking->room ? $booking->room->name : '*(Hanya meminjam Aset)*';

            $pesan = "*NOTIFIKASI PAROKI PAULUS MIKI*\n\n";
            $pesan .= "Berkah Dalem Bpk/Ibu *{$booking->user->name}*,\n\n";
            $pesan .= "Pengajuan Anda telah *DISETUJUI* dengan rincian:\n\n";
            $pesan .= "🏢 Fasilitas: $namaRuangan\n";
            $pesan .= "📅 Tanggal: $tanggal\n";
            $pesan .= "⏰ Waktu: $jam\n";
            $pesan .= "🎯 Kegiatan: {$booking->purpose}\n\n";

            if ($booking->assets->count() > 0) {
                $pesan .= "📦 *Aset yang dipinjam:*\n";
                foreach ($booking->assets as $asset) {
                    $pesan .= "- {$asset->asset_name} ({$asset->pivot->quantity} unit)\n";
                }
                $pesan .= "\n";
            }
            
            if ($request->admin_note) {
                $pesan .= "📝 *Catatan Admin:* {$request->admin_note}\n\n";
            }
            
            $pesan .= "Silakan gunakan fasilitas dengan baik. Terima kasih.\n\n_Sistem Reservasi Paroki Paulus Miki_";

            // Eksekusi API WA
            try {
                Http::withoutVerifying()->withHeaders([
                    'Authorization' => env('FONNTE_TOKEN'),
                ])->post('https://api.fonnte.com/send', [
                    'target' => $booking->user->phone_number,
                    'message' => $pesan,
                ]);
            } catch (\Exception $e) {
                // Abaikan jika WA gagal agar aplikasi tidak error
            }
        }
        // --- SELESAI SCRIPT FONNTE ---

        return redirect()->back()->with('success', 'Pengajuan disetujui, Stok Aset dikurangi & Pesan WA telah terkirim!');
    }

    // FUNGSI BARU: MENYELESAIKAN KEGIATAN & MENGEMBALIKAN ASET
    public function complete(Request $request, $id)
    {
        $booking = Booking::with('assets')->findOrFail($id);

        if ($booking->status !== 'approved') {
            return redirect()->back()->with('error', 'Hanya jadwal yang berstatus "Disetujui" yang dapat diselesaikan.');
        }

        // KEMBALIKAN STOK ASET KE INVENTARIS
        foreach ($booking->assets as $asset) {
            $asset->increment('stock_available', $asset->pivot->quantity);
        }

        $booking->update([
            'status' => 'completed'
        ]);

        Log::create([
            'booking_id' => $booking->id,
            'actor_id' => auth()->id(),
            'action' => 'completed',
        ]);

        return redirect()->back()->with('success', 'Kegiatan Selesai! Stok aset telah berhasil dikembalikan ke inventaris Paroki.');
    }

    // Fungsi untuk Admin Menolak Jadwal
    public function reject(Request $request, $id) // Tambahkan Request $request
    {
        $booking = Booking::with(['user', 'room'])->findOrFail($id);

        // Update Status & Simpan Catatan Admin
        $booking->update([
            'status' => 'rejected',
            'admin_note' => $request->admin_note
        ]);

        // Catat jejak audit di tabel logs
        Log::create([
            'booking_id' => $booking->id,
            'actor_id' => auth()->id(),
            'action' => 'rejected',
        ]);

        // --- MULAI SCRIPT FONNTE API WA ---
        if ($booking->user->phone_number) { // Pastikan menggunakan phone_number
            $tanggal = Carbon::parse($booking->start_time)->translatedFormat('d F Y');

            $pesan = "*NOTIFIKASI PAROKI PAULUS MIKI*\n\n";
            $pesan .= "Mohon maaf Bpk/Ibu *{$booking->user->name}*,\n\n";
            $pesan .= "Pengajuan ruangan *{$booking->room->name}* untuk tanggal $tanggal *TIDAK DAPAT DISETUJUI / DITOLAK*.\n\n";

            // Tambahkan Alasan/Catatan Admin ke pesan WA jika ada
            if ($request->admin_note) {
                $pesan .= "📌 *Alasan Penolakan:* {$request->admin_note}\n\n";
            }

            $pesan .= "Silakan hubungi Sekretariat untuk informasi lebih lanjut.\n\n";
            $pesan .= "_Sistem Reservasi Paroki Paulus Miki_";

            $response = Http::withoutVerifying()->withHeaders([
                'Authorization' => env('FONNTE_TOKEN'),
            ])->post('https://api.fonnte.com/send', [
                        'target' => $booking->user->phone_number, // Pastikan menggunakan phone_number
                        'message' => $pesan,
                    ]);

            // Buka komentar baris di bawah ini HANYA JIKA WA belum masuk, untuk mengecek pesan error dari Fonnte
            // dd($response->json());
        }
        // --- SELESAI SCRIPT FONNTE ---

        return redirect()->back()->with('error', 'Jadwal ditolak & Pesan WA pemberitahuan telah dikirim.');
    }
}