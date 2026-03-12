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
        // Load relasi room DAN assets
        $booking = Booking::with(['user', 'room', 'assets'])->findOrFail($id);

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

            // Kondisi tampilan nama ruangan jika null
            $namaRuangan = $booking->room ? $booking->room->name : '*(Hanya meminjam Aset)*';

            $pesan = "*NOTIFIKASI PAROKI PAULUS MIKI*\n\n";
            $pesan .= "Berkah Dalem Bpk/Ibu *{$booking->user->name}*,\n\n";
            $pesan .= "Pengajuan Anda telah *DISETUJUI* dengan rincian:\n\n";
            $pesan .= "🏢 Ruangan: $namaRuangan\n";
            $pesan .= "📅 Tanggal: $tanggal\n";
            $pesan .= "⏰ Waktu: $jam\n";
            $pesan .= "🎯 Kegiatan: {$booking->purpose}\n\n";

            // Tambahkan rincian aset jika ada
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

            $response = Http::withoutVerifying()->withHeaders([
                'Authorization' => env('FONNTE_TOKEN'),
            ])->post('https://api.fonnte.com/send', [
                        'target' => $booking->user->phone_number,
                        'message' => $pesan,
                    ]);
        }
        // --- SELESAI SCRIPT FONNTE ---

        return redirect()->back()->with('success', 'Pengajuan disetujui & Pesan WA telah terkirim!');
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