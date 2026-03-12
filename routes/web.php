<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\Room;
use App\Http\Controllers\BookingController;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AssetController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $rooms = \App\Models\Room::all();
    $user = \Illuminate\Support\Facades\Auth::user();

    // Jika yang login adalah UMAT
    if ($user->role == 'umat') {
        $myBookings = \App\Models\Booking::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')->get();
        return view('dashboard', compact('rooms', 'myBookings'));
    }
    // Jika yang login adalah SEKRETARIAT / ADMIN
// Jika yang login adalah SEKRETARIAT / ADMIN
    else {
        // 1. Ambil antrean jadwal yang masih 'pending'
        $pendingBookings = \App\Models\Booking::with(['user', 'room'])
                            ->where('status', 'pending')
                            ->orderBy('created_at', 'asc')->get();

        // 2. Ambil jadwal yang sudah 'approved' untuk kegiatan mendatang (Mulai dari hari ini ke depan)
        $upcomingBookings = \App\Models\Booking::with(['user', 'room'])
                            ->where('status', 'approved')
                            ->whereDate('start_time', '>=', \Carbon\Carbon::today())
                            ->orderBy('start_time', 'asc') // Urutkan berdasarkan tanggal mainnya terdekat
                            ->take(10) // Tampilkan maksimal 10 jadwal terdekat agar tidak terlalu panjang
                            ->get();

        // Lempar kedua variabel tersebut ke tampilan dashboard
        return view('dashboard', compact('rooms', 'pendingBookings', 'upcomingBookings'));
    }
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
// Rute untuk melihat form peminjaman (hanya untuk user yang sudah login)
Route::get('/booking/{room}', [BookingController::class, 'create'])->middleware(['auth', 'verified'])->name('booking.create');
// Rute untuk memproses dan menyimpan data form
Route::post('/booking/{room}', [BookingController::class, 'store'])->middleware(['auth', 'verified'])->name('booking.store');
// Rute untuk mengeksekusi persetujuan admin
Route::post('/booking/{id}/approve', [BookingController::class, 'approve'])->name('booking.approve');
Route::post('/booking/{id}/reject', [BookingController::class, 'reject'])->name('booking.reject');
// Rute Detail Ruangan (Semua user boleh akses)
Route::get('/rooms/{room}/detail', [App\Http\Controllers\RoomController::class, 'show'])->middleware(['auth', 'verified'])->name('rooms.show');
// Rute Khusus Manajemen Ruangan (Hanya untuk Admin & Sekretariat)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
    Route::get('/rooms/create', [RoomController::class, 'create'])->name('rooms.create');
    Route::post('/rooms', [RoomController::class, 'store'])->name('rooms.store');
    Route::get('/rooms/{room}/edit', [RoomController::class, 'edit'])->name('rooms.edit');
    Route::patch('/rooms/{room}', [RoomController::class, 'update'])->name('rooms.update');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export-csv', [ReportController::class, 'exportCsv'])->name('reports.export');
});
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('assets', AssetController::class);
});
require __DIR__ . '/auth.php';
