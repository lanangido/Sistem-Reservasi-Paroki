<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Data Aset: ') }} <span class="text-indigo-600">{{ $asset->asset_name }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">

                    @if($errors->any())
                        <div class="mb-4 bg-red-50 text-red-700 p-4 rounded border border-red-200">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>- {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('assets.update', $asset->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 gap-6">

                            <div class="grid grid-cols-2 gap-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Kode Aset</label>
                                        <input type="text" value="{{ $asset->asset_code }}" disabled
                                            class="w-full rounded-md border-gray-300 shadow-sm bg-gray-100 text-gray-600 font-mono cursor-not-allowed">
                                        <p class="text-xs text-gray-500 mt-1">Kode aset bersifat permanen.</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Aset</label>
                                        <input type="text" name="asset_name"
                                            value="{{ old('asset_name', $asset->asset_name) }}" required
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            placeholder="Contoh: Sound System Yamaha">
                                    </div>
                                </div>

                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi Penyimpanan
                                    (Opsional)</label>
                                <select name="room_id"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">-- Gudang Umum / Tidak Tertaut Ruangan --</option>
                                    @foreach($rooms as $room)
                                        <option value="{{ $room->id }}" {{ old('room_id', $asset->room_id) == $room->id ? 'selected' : '' }}>{{ $room->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Total Stok</label>
                                    <input type="number" name="stock_total"
                                        value="{{ old('stock_total', $asset->stock_total) }}" min="1" required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <p class="text-xs text-gray-500 mt-1">Stok saat ini:
                                        <strong>{{ $asset->stock_available }}</strong> unit tersedia untuk dipinjam.</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Kondisi Aset</label>
                                    <select name="condition" required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="good" {{ old('condition', $asset->condition) == 'good' ? 'selected' : '' }}>Baik</option>
                                        <option value="broken" {{ old('condition', $asset->condition) == 'broken' ? 'selected' : '' }}>Rusak</option>
                                        <option value="maintenance" {{ old('condition', $asset->condition) == 'maintenance' ? 'selected' : '' }}>Sedang Perbaikan
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Tambahan</label>
                                <textarea name="description" rows="3"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $asset->description) }}</textarea>
                            </div>

                        </div>

                        <div class="mt-6 flex justify-end gap-3">
                            <a href="{{ route('assets.index') }}"
                                class="bg-gray-200 text-gray-800 px-4 py-2 rounded shadow-sm hover:bg-gray-300 font-bold">Batal</a>
                            <button type="submit"
                                class="bg-indigo-600 text-white px-4 py-2 rounded shadow-sm hover:bg-indigo-700 font-bold">Simpan
                                Perubahan</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>