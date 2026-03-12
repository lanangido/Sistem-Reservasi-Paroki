@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white shadow-md rounded-lg overflow-hidden">
        <div class="bg-blue-600 px-6 py-4">
            <h2 class="text-white text-xl font-bold">Tambah Aset Baru</h2>
        </div>
        
        <form action="{{ route('assets.store') }}" method="POST" class="p-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nama Aset</label>
                    <input type="text" name="asset_name" required placeholder="Contoh: Kursi Lipat"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Kode Aset (Unik)</label>
                    <input type="text" name="asset_code" required placeholder="Contoh: K-AUL-001"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Lokasi Ruangan</label>
                <select name="room_id" required class="w-full px-3 py-2 border rounded-lg focus:outline-none">
                    <option value="">-- Pilih Lokasi Aset --</option>
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}">{{ $room->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Total Stok</label>
                    <input type="number" name="stock_total" required min="1"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Kondisi Barang</label>
                    <select name="condition" class="w-full px-3 py-2 border rounded-lg focus:outline-none">
                        <option value="good">BAGUS (Layanan Oke)</option>
                        <option value="broken">RUSAK</option>
                        <option value="maintenance">DALAM PERBAIKAN</option>
                    </select>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Deskripsi / Merk (Opsional)</label>
                <textarea name="description" rows="3" class="w-full px-3 py-2 border rounded-lg focus:outline-none"></textarea>
            </div>

            <div class="flex justify-end gap-4">
                <a href="{{ route('assets.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition">Batal</a>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">Simpan Aset</button>
            </div>
        </form>
    </div>
</div>
@endsection