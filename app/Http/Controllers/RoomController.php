<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    // 1. Menampilkan Halaman Kelola Ruangan (Read)
    public function index()
    {
        $rooms = Room::orderBy('name', 'asc')->get();
        return view('rooms.index', compact('rooms'));
    }
    // Menampilkan Halaman Detail Ruangan (Bisa diakses Umat)
public function show(Room $room)
    {
        // Ambil semua jadwal kegiatan MENDATANG yang sudah disetujui untuk ruangan ini
        $upcomingBookings = \App\Models\Booking::with('user')
            ->where('room_id', $room->id)
            ->where('status', 'approved')
            ->whereDate('start_time', '>=', \Carbon\Carbon::today())
            ->orderBy('start_time', 'asc')
            ->get();

        return view('rooms.show', compact('room', 'upcomingBookings'));
    }

    // 2. Menampilkan Form Tambah Ruangan (Create)
    public function create()
    {
        return view('rooms.create');
    }

    // 3. Memproses Data Ruangan Baru + Gambar (Store)
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Pastikan validasi image ada
        ]);

        // 2. Olah Gambar jika ada yang diupload
        $imagePath = null;
        if ($request->hasFile('image')) {
            // Simpan ke folder public/rooms
            $imagePath = $request->file('image')->store('rooms', 'public');
        }

        // 3. Simpan ke Database
        Room::create([
            'name' => $request->name,
            'capacity' => $request->capacity,
            'description' => $request->description,
            'image' => $imagePath, // Masukkan path gambar di sini
            'is_active' => true,
        ]);

        return redirect()->route('rooms.index')->with('success', 'Ruangan berhasil ditambahkan!');
    }

    // 4. Menampilkan Form Edit Ruangan (Edit)
    public function edit(Room $room)
    {
        return view('rooms.edit', compact('room'));
    }

    // 5. Memproses Perubahan Data + Ganti Gambar (Update)
    public function update(Request $request, Room $room)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();

        // Jika admin mengganti gambar
        if ($request->hasFile('image')) {
            // Hapus gambar lama (jika ada) dari server agar tidak memenuhi memori
            if ($room->image) {
                Storage::disk('public')->delete($room->image);
            }
            // Simpan gambar yang baru
            $imagePath = $request->file('image')->store('rooms', 'public');
            $data['image'] = $imagePath;
        }

        $room->update($data);

        return redirect()->route('rooms.index')->with('success', 'Data ruangan berhasil diperbarui!');
    }
}