<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Room;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    // 1. Menampilkan semua aset
    public function index()
    {
        $assets = Asset::with('room')->get(); // Mengambil data aset beserta info ruangannya
        return view('admin.assets.index', compact('assets'));
    }

    // 2. Menampilkan form tambah aset
    public function create()
    {
        $rooms = Room::all(); // Ambil daftar ruangan untuk pilihan (dropdown)
        return view('admin.assets.create', compact('rooms'));
    }

    // 3. Menyimpan aset baru
    public function store(Request $request)
    {
        $request->validate([
            'asset_name' => 'required|string|max:255',
            'asset_code' => 'required|string|unique:assets,asset_code',
            'room_id' => 'required|exists:rooms,id',
            'stock_total' => 'required|integer|min:1',
            'condition' => 'required|in:good,broken,maintenance',
        ]);

        Asset::create([
            'asset_name' => $request->asset_name,
            'asset_code' => $request->asset_code,
            'room_id' => $request->room_id,
            'stock_total' => $request->stock_total,
            'stock_available' => $request->stock_total, // Awalnya stok tersedia = stok total
            'condition' => $request->condition,
            'description' => $request->description,
        ]);

        return redirect()->route('assets.index')->with('success', 'Aset berhasil ditambahkan ke inventaris!');
    }

    // 4. Menampilkan form edit (Opsional tapi penting)
    public function edit(Asset $asset)
    {
        $rooms = Room::all();
        return view('admin.assets.edit', compact('asset', 'rooms'));
    }

    // 5. Update data aset
    public function update(Request $request, Asset $asset)
    {
        $request->validate([
            'asset_name' => 'required|string|max:255',
            'room_id' => 'required|exists:rooms,id',
            'stock_total' => 'required|integer|min:1',
            'condition' => 'required|in:good,broken,maintenance',
        ]);

        $asset->update($request->all());

        return redirect()->route('assets.index')->with('success', 'Data aset berhasil diperbarui!');
    }

    // 6. Hapus aset
    public function destroy(Asset $asset)
    {
        $asset->delete();
        return redirect()->route('assets.index')->with('success', 'Aset telah dihapus.');
    }
}