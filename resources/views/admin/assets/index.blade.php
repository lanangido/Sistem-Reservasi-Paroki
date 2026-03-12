<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Aset Paroki') }}
            </h2>
            <a href="{{ route('assets.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-5 rounded-xl transition-all shadow-md hover:-translate-y-0.5 text-sm flex items-center gap-2">
                + Tambah Aset Baru
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-100 text-gray-700 text-sm uppercase">
                                    <th class="p-4 border-b">Kode</th>
                                    <th class="p-4 border-b">Nama Aset</th>
                                    <th class="p-4 border-b">Lokasi (Ruangan)</th>
                                    <th class="p-4 border-b text-center">Total Stok</th>
                                    <th class="p-4 border-b text-center">Tersedia</th>
                                    <th class="p-4 border-b text-center">Kondisi</th>
                                    <th class="p-4 border-b text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($assets as $asset)
                                    <tr class="hover:bg-gray-50 text-sm">
                                        <td class="p-4 border-b font-mono text-indigo-600">{{ $asset->asset_code }}</td>
                                        <td class="p-4 border-b font-bold text-gray-800">{{ $asset->asset_name }}</td>
                                        <td class="p-4 border-b">{{ $asset->room ? $asset->room->name : 'Gudang Umum' }}</td>
                                        <td class="p-4 border-b text-center font-bold">{{ $asset->stock_total }}</td>
                                        <td class="p-4 border-b text-center">
                                            <span class="px-2 py-1 rounded-full text-xs font-bold {{ $asset->stock_available > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $asset->stock_available }}
                                            </span>
                                        </td>
                                        <td class="p-4 border-b text-center">
                                            @if($asset->condition == 'good')
                                                <span class="text-green-600 font-semibold">Baik</span>
                                            @elseif($asset->condition == 'broken')
                                                <span class="text-red-600 font-semibold">Rusak</span>
                                            @else
                                                <span class="text-yellow-600 font-semibold">Maintenance</span>
                                            @endif
                                        </td>
                                        <td class="p-4 border-b text-center flex justify-center gap-2">
                                            <a href="{{ route('assets.edit', $asset->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-bold py-1 px-3 rounded">Edit</a>
                                            <form action="{{ route('assets.destroy', $asset->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus aset ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white text-xs font-bold py-1 px-3 rounded">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="p-6 text-center text-gray-500">Belum ada data aset yang terdaftar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>