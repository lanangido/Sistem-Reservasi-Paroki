@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Manajemen Aset Paroki</h2>
        <a href="{{ route('assets.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            + Tambah Aset Baru
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full leading-normal">
            <thead>
                <tr class="bg-gray-100 border-b">
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Kode</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Nama Aset</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Lokasi Ruang</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Stok</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Kondisi</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($assets as $asset)
                <tr class="border-b">
                    <td class="px-5 py-4 text-sm">{{ $asset->asset_code }}</td>
                    <td class="px-5 py-4 text-sm font-bold">{{ $asset->asset_name }}</td>
                    <td class="px-5 py-4 text-sm">{{ $asset->room->name }}</td>
                    <td class="px-5 py-4 text-sm">{{ $asset->stock_total }}</td>
                    <td class="px-5 py-4 text-sm">
                        <span class="px-2 py-1 rounded text-xs {{ $asset->condition == 'good' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                            {{ strtoupper($asset->condition) }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-sm">
                        <form action="{{ route('assets.destroy', $asset->id) }}" method="POST" onsubmit="return confirm('Hapus aset ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection