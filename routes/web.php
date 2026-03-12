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
use App\Http\Controllers\UserController;
use App\Http\Controllers\LogController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $rooms = \App\Models\Room::all();
    $user = \Illuminate\Support\Facades\Auth::user();

    // Jika yang login adalah UMAT
    if ($user->role == 'umat') {
        // Ditambahkan eager loading 'room' dan 'assets' agar dashboard umat tidak berat saat loading
        $myBookings = \App\Models\Booking::with(['room', 'assets'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')->get();
            
        return view('dashboard', compact('rooms', 'myBookings'));
    }
    // Jika yang login adalah SEKRETARIAT / ADMIN
    else {
        // 1. Ambil antrean jadwal yang masih 'pending' (Tambahkan relasi 'assets')
        $pendingBookings = \App\Models\Booking::with(['user', 'room', 'assets'])
                            ->where('status', 'pending')
                            ->orderBy('created_at', 'asc')->get();

        // 2. Ambil jadwal yang sudah 'approved' untuk kegiatan mendatang (Tambahkan relasi 'assets')
        $upcomingBookings = \App\Models\Booking::with(['user', 'room', 'assets'])
                            ->where('status', 'approved')
                            ->whereDate('start_time', '>=', \Carbon\Carbon::today())
                            ->orderBy('start_time', 'asc') // Urutkan berdasarkan tanggal mainnya terdekat
                            ->take(10) // Tampilkan maksimal 10 jadwal terdekat agar tidak terlalu panjang
                            ->get();

        // Lempar variabel tersebut ke tampilan dashboard
        return view('dashboard', compact('rooms', 'pendingBookings', 'upcomingBookings'));
    }
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --- RUTE PEMINJAMAN (BOOKING) BARU ---
// Rute untuk melihat form peminjaman (Parameter {room} Dihapus)
Route::get('/booking/create', [BookingController::class, 'create'])->middleware(['auth', 'verified'])->name('bookings.create');

// Rute untuk memproses dan menyimpan data form (Parameter {room} Dihapus)
Route::post('/bookings', [BookingController::class, 'store'])->middleware(['auth', 'verified'])->name('bookings.store');


// Rute untuk mengeksekusi persetujuan admin
Route::post('/booking/{id}/approve', [BookingController::class, 'approve'])->name('booking.approve');
Route::post('/booking/{id}/reject', [BookingController::class, 'reject'])->name('booking.reject');
Route::post('/booking/{id}/complete', [BookingController::class, 'complete'])->name('booking.complete');

// Rute Detail Ruangan (Semua user boleh akses)
Route::get('/rooms/{room}/detail', [RoomController::class, 'show'])->middleware(['auth', 'verified'])->name('rooms.show');

// Rute Khusus Manajemen Ruangan (Hanya untuk Admin & Sekretariat)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
    Route::get('/rooms/create', [RoomController::class, 'create'])->name('rooms.create');
    Route::post('/rooms', [RoomController::class, 'store'])->name('rooms.store');
    Route::get('/rooms/{room}/edit', [RoomController::class, 'edit'])->name('rooms.edit');
    Route::patch('/rooms/{room}', [RoomController::class, 'update'])->name('rooms.update');
    
    // Rute Laporan (Duplikat route dihapus)
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export-csv', [ReportController::class, 'exportCsv'])->name('reports.export');
});

// Rute Khusus Manajemen Aset
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('assets', AssetController::class);
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('assets', AssetController::class);
    Route::resource('users', UserController::class); // <-- Tambahkan baris ini
});

Route::get('/logs', [\App\Http\Controllers\LogController::class, 'index'])->name('logs.index');
Route::middleware(['auth'])->group(function () {
    // Tambahkan pengecekan role secara manual di dalam controller 
    // atau gunakan middleware custom jika sudah punya.
    Route::get('/admin/logs', [LogController::class, 'index'])
        ->name('admin.logs')
        ->middleware('can:admin-only'); 
});

require __DIR__ . '/auth.php';