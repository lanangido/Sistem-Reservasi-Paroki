<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Models\Room;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Models\Log;

class BookingController extends Controller
{
    // Fungsi untuk menampilkan form peminjaman
    public function create(Room $room)
    {
        // Lempar data ruangan yang dipilih ke halaman form
        return view('bookings.create', compact('room'));
    }

    public function store(Request $request, Room $room)
    {
        // 1. Validasi Input Dasar
        $request->validate([
            'purpose' => 'required|string|max:255',
            'attendees' => 'required|integer|min:1|max:' . $room->capacity,
            'start_time' => 'required|date|after_or_equal:today',
            'end_time' => 'required|date|after:start_time',
            'sop_agreement' => 'accepted' // Memastikan checkbox SOP dicentang
        ]);

        // 2. LOGIKA DETEKSI KONFLIK (Double Booking)
        $conflict = Booking::where('room_id', $room->id)
            ->whereIn('status', ['pending', 'approved']) // Hanya cek jadwal yang aktif
            ->where(function ($query) use ($request) {
                // Skenario 1: Waktu mulai/selesai yang baru menabrak jadwal yang sudah ada
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                    ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                    // Skenario 2: Waktu yang baru "membungkus" jadwal yang sudah ada di tengah-tengahnya
                    ->orWhere(function ($q) use ($request) {
                    $q->where('start_time', '<=', $request->start_time)
                        ->where('end_time', '>=', $request->end_time);
                });
            })->exists();

        // Jika bentrok, tolak dan kembalikan pesan error
        if ($conflict) {
            return back()->withInput()->withErrors([
                'start_time' => 'Maaf, ruangan sudah terpesan pada jam tersebut. Silakan geser waktu kegiatan Anda.'
            ]);
        }

        // 3. Jika aman (Logika FIFO: Yang datanya masuk database duluan, dia yang dapat)
        Booking::create([
            'user_id' => auth()->id(),
            'room_id' => $room->id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'purpose' => $request->purpose,
            'attendees' => $request->attendees,
            'status' => 'pending', // Status otomatis pending untuk direview Sekretariat
        ]);

        // 4. Kembali ke Dashboard dengan pesan sukses
        return redirect()->route('dashboard')->with('success', 'Pengajuan jadwal berhasil dikirim! Silakan tunggu konfirmasi Sekretariat.');
    }

    // Fungsi untuk Admin Menyetujui Jadwal
    public function approve(Request $request, $id) // Tambahkan Request $request
    {
        $booking = Booking::with(['user', 'room'])->findOrFail($id);

        // Update Status & Simpan Catatan Admin
        $booking->update([
            'status' => 'approved',
            'admin_note' => $request->admin_note
        ]);

        // Catat jejak audit di tabel logs
        Log::create([
            'booking_id' => $booking->id,
            'actor_id' => auth()->id(),
            'action' => 'approved',
        ]);

        // --- MULAI SCRIPT FONNTE API WA ---
        if ($booking->user->phone_number) {
            $tanggal = Carbon::parse($booking->start_time)->translatedFormat('l, d F Y');
            $jam = Carbon::parse($booking->start_time)->format('H:i') . ' - ' . Carbon::parse($booking->end_time)->format('H:i') . ' WIB';

            $pesan = "*NOTIFIKASI PAROKI PAULUS MIKI*\n\n";
            $pesan .= "Berkah Dalem Bpk/Ibu *{$booking->user->name}*,\n\n";
            $pesan .= "Pengajuan ruangan Anda telah *DISETUJUI* dengan rincian:\n\n";
            $pesan .= "🏛️ Ruangan: *{$booking->room->name}*\n";
            $pesan .= "📅 Tanggal: $tanggal\n";
            $pesan .= "⏰ Waktu: $jam\n";
            $pesan .= "📝 Kegiatan: {$booking->purpose}\n\n";
            
            // Tambahkan Catatan Admin ke pesan WA jika ada
            if ($request->admin_note) {
                $pesan .= "📌 *Catatan Admin:* {$request->admin_note}\n\n";
            }
            
            $pesan .= "Silakan gunakan fasilitas dengan baik dan menjaga kebersihan. Terima kasih.\n\n";
            $pesan .= "_Sistem Reservasi Paroki Paulus Miki_";

           $response = Http::withoutVerifying()->withHeaders([
                'Authorization' => env('FONNTE_TOKEN'),
            ])->post('https://api.fonnte.com/send', [
                'target' => $booking->user->phone_number,
                'message' => $pesan,
            ]);

            // 2. TAMBAHKAN BARIS INI UNTUK MENJEBAK JAWABAN FONNTE
            dd($response->json());
            
            // Buka komentar baris di bawah ini HANYA JIKA WA belum masuk, untuk mengecek pesan error dari Fonnte
            // dd($response->json());
        }
        // --- SELESAI SCRIPT FONNTE ---

        return redirect()->back()->with('success', 'Jadwal disetujui & Pesan WA telah terkirim!');
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