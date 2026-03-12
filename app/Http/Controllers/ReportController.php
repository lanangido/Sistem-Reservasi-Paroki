<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);
        $search = $request->input('search');

        // TAMBAHAN: Eager load relasi 'assets' agar tidak berat
        $query = Booking::with(['user', 'room', 'assets'])
                    ->whereMonth('start_time', $month)
                    ->whereYear('start_time', $year);

        if ($search) {
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('lingkungan', 'like', '%' . $search . '%');
            });
        }

        $bookings = $query->orderBy('start_time', 'desc')->get();

        $totalBookings = $bookings->count();
        $approvedBookings = $bookings->where('status', 'approved')->count();
        $rejectedBookings = $bookings->where('status', 'rejected')->count();
        $pendingBookings = $bookings->where('status', 'pending')->count();  

        $chartRoomData = $bookings->whereIn('status', ['approved', 'completed'])
            ->groupBy(function($booking) {
                return $booking->room ? $booking->room->name : 'Hanya Aset';
            })
            ->map->count();

        // DATA UNTUK GRAFIK: Distribusi Kategori Kegiatan
        $chartPurposeData = $bookings->whereIn('status', ['approved', 'completed'])
            ->groupBy('purpose')
            ->map->count();

        return view('reports.index', compact(
            'bookings', 'month', 'year', 'search', 'totalBookings', 'approvedBookings', 'rejectedBookings', 'pendingBookings',
            'chartRoomData', 'chartPurposeData' // <-- Tambahkan 2 variabel ini
        ));

        return view('reports.index', compact(
            'bookings', 'month', 'year', 'search', 'totalBookings', 'approvedBookings', 'rejectedBookings', 'pendingBookings'
        ));
    }

    public function exportCsv(Request $request)
    {
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);
        $search = $request->input('search');

        // TAMBAHAN: Eager load relasi 'assets'
        $query = Booking::with(['user', 'room', 'assets'])
                    ->whereMonth('start_time', $month)
                    ->whereYear('start_time', $year);

        if ($search) {
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('lingkungan', 'like', '%' . $search . '%');
            });
        }

        $bookings = $query->orderBy('start_time', 'desc')->get();

        $fileName = "Laporan_Reservasi_Paroki_{$month}_{$year}.csv";

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        // TAMBAHAN: Menambahkan Header Kolom 'Aset'
        $columns = array('Tanggal Mulai', 'Waktu', 'Ruangan', 'Aset yang Dipinjam', 'Nama Peminjam', 'Lingkungan', 'Kegiatan', 'Jumlah Peserta', 'Status');

        $callback = function() use($bookings, $columns) {
            $file = fopen('php://output', 'w');
            // Menambahkan BOM (Byte Order Mark) agar karakter khusus rapi saat dibuka di MS Excel
            fputs($file, $bom =(chr(0xEF) . chr(0xBB) . chr(0xBF)));
            
            fputcsv($file, $columns); 

            foreach ($bookings as $booking) {
                $row['Tanggal Mulai']  = Carbon::parse($booking->start_time)->format('d/m/Y');
                $row['Waktu']          = Carbon::parse($booking->start_time)->format('H:i') . ' - ' . Carbon::parse($booking->end_time)->format('H:i');
                
                // Pengecekan Ruangan (Mencegah Error Null)
                $row['Ruangan']        = $booking->room ? $booking->room->name : 'Tanpa Ruangan';
                
                // Menyatukan rincian aset menjadi 1 baris string (Contoh: "Sound System (1), Kursi (20)")
                $assetRincian = [];
                foreach($booking->assets as $asset) {
                    $assetRincian[] = $asset->asset_name . ' (' . $asset->pivot->quantity . 'x)';
                }
                $row['Aset yang Dipinjam'] = empty($assetRincian) ? '-' : implode(', ', $assetRincian);

                $row['Nama Peminjam']  = $booking->user->name;
                $row['Lingkungan']     = $booking->user->lingkungan ?? '-';
                $row['Kegiatan']       = $booking->purpose;
                $row['Jumlah Peserta'] = $booking->attendees;
                $statusIndo = $booking->status == 'approved' ? 'Disetujui' : ($booking->status == 'rejected' ? 'Ditolak' : 'Menunggu');
                $row['Status']         = $statusIndo;

                fputcsv($file, array(
                    $row['Tanggal Mulai'], 
                    $row['Waktu'], 
                    $row['Ruangan'], 
                    $row['Aset yang Dipinjam'], 
                    $row['Nama Peminjam'], 
                    $row['Lingkungan'], 
                    $row['Kegiatan'], 
                    $row['Jumlah Peserta'], 
                    $row['Status']
                ));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}