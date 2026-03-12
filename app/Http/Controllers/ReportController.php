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
        // Tambahkan tangkapan untuk filter pencarian
        $search = $request->input('search');

        // Buat query dasar
        $query = Booking::with(['user', 'room'])
                    ->whereMonth('start_time', $month)
                    ->whereYear('start_time', $year);

        // Jika ada input pencarian, filter berdasarkan nama user atau lingkungan
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

        return view('reports.index', compact(
            'bookings', 'month', 'year', 'search', 'totalBookings', 'approvedBookings', 'rejectedBookings', 'pendingBookings'
        ));
    }

    // Fungsi khusus untuk men-download file CSV
    public function exportCsv(Request $request)
    {
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);
        $search = $request->input('search');

        $query = Booking::with(['user', 'room'])
                    ->whereMonth('start_time', $month)
                    ->whereYear('start_time', $year);

        if ($search) {
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('lingkungan', 'like', '%' . $search . '%');
            });
        }

        $bookings = $query->orderBy('start_time', 'desc')->get();

        // Siapkan nama file
        $fileName = "Laporan_Reservasi_Paroki_{$month}_{$year}.csv";

        // Buat header untuk CSV
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('Tanggal Mulai', 'Waktu', 'Ruangan', 'Nama Peminjam', 'Lingkungan', 'Kegiatan', 'Jumlah Peserta', 'Status');

        $callback = function() use($bookings, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns); // Tulis baris judul tabel

            foreach ($bookings as $booking) {
                $row['Tanggal Mulai']  = Carbon::parse($booking->start_time)->format('d/m/Y');
                $row['Waktu']          = Carbon::parse($booking->start_time)->format('H:i') . ' - ' . Carbon::parse($booking->end_time)->format('H:i');
                $row['Ruangan']        = $booking->room->name;
                $row['Nama Peminjam']  = $booking->user->name;
                $row['Lingkungan']     = $booking->user->lingkungan ?? '-';
                $row['Kegiatan']       = $booking->purpose;
                $row['Jumlah Peserta'] = $booking->attendees;
                // Terjemahkan status agar rapi di Excel
                $statusIndo = $booking->status == 'approved' ? 'Disetujui' : ($booking->status == 'rejected' ? 'Ditolak' : 'Menunggu');
                $row['Status']         = $statusIndo;

                fputcsv($file, array($row['Tanggal Mulai'], $row['Waktu'], $row['Ruangan'], $row['Nama Peminjam'], $row['Lingkungan'], $row['Kegiatan'], $row['Jumlah Peserta'], $row['Status']));
            }

            fclose($file);
        };

        // Kembalikan sebagai file download
        return response()->stream($callback, 200, $headers);
    }
}