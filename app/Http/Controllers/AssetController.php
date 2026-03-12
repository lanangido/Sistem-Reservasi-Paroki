<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Room;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index()
    {
        $assets = Asset::with('room')->latest()->get();
        return view('admin.assets.index', compact('assets'));
    }

    public function create()
    {
        $rooms = Room::all(); 
        return view('admin.assets.create', compact('rooms'));
    }

    public function store(Request $request)
    {
        // Validasi input (asset_code dihapus dari validasi karena dibuat otomatis)
        $request->validate([
            'asset_name' => 'required|string|max:255',
            'room_id' => 'nullable|exists:rooms,id',
            'stock_total' => 'required|integer|min:1',
            'condition' => 'required|in:good,broken,maintenance',
            'description' => 'nullable|string'
        ]);

        // LOGIKA KODE OTOMATIS: Cari aset terakhir yang diinput
        $lastAsset = Asset::orderBy('id', 'desc')->first();
        
        // Ekstrak angka dari kode aset terakhir (contoh: dari AST-0012 ambil angka 12)
        if ($lastAsset && preg_match('/AST-(\d+)/', $lastAsset->asset_code, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        } else {
            $nextNumber = 1; // Jika belum ada aset sama sekali, mulai dari 1
        }
        
        // Format kode baru menjadi AST-XXXX (contoh: AST-0001)
        $assetCode = 'AST-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        Asset::create([
            'asset_name' => $request->asset_name,
            'asset_code' => $assetCode, // Masukkan kode otomatis ke database
            'room_id' => $request->room_id,
            'stock_total' => $request->stock_total,
            'stock_available' => $request->stock_total,
            'condition' => $request->condition,
            'description' => $request->description,
        ]);

        return redirect()->route('assets.index')->with('success', 'Aset baru berhasil ditambahkan dengan kode ' . $assetCode);
    }

    public function edit(Asset $asset)
    {
        $rooms = Room::all();
        return view('admin.assets.edit', compact('asset', 'rooms'));
    }

    public function update(Request $request, Asset $asset)
    {
        // Validasi input (asset_code dihapus dari validasi)
        $request->validate([
            'asset_name' => 'required|string|max:255',
            'room_id' => 'nullable|exists:rooms,id',
            'stock_total' => 'required|integer|min:1',
            'condition' => 'required|in:good,broken,maintenance',
            'description' => 'nullable|string'
        ]);

        $selisihStok = $request->stock_total - $asset->stock_total;
        $stockAvailableBaru = $asset->stock_available + $selisihStok;

        if ($stockAvailableBaru < 0) {
            return back()->withInput()->withErrors(['stock_total' => 'Total stok tidak valid karena aset sedang dipinjam.']);
        }

        $asset->update([
            'asset_name' => $request->asset_name,
            // asset_code sengaja tidak di-update agar tidak berubah
            'room_id' => $request->room_id,
            'stock_total' => $request->stock_total,
            'stock_available' => $stockAvailableBaru,
            'condition' => $request->condition,
            'description' => $request->description,
        ]);

        return redirect()->route('assets.index')->with('success', 'Data aset berhasil diperbarui.');
    }

    public function destroy(Asset $asset)
    {
        $asset->delete();
        return redirect()->route('assets.index')->with('success', 'Data aset berhasil dihapus.');
    }
}