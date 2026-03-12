<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Aset Ruangan') }}
            </h2>
            <a href="{{ route('rooms.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-5 rounded-xl transition-all shadow-md hover:-translate-y-0.5 text-sm flex items-center gap-2">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Tambah Ruangan Baru
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative shadow-sm" role="alert">
                    <strong class="font-bold">Berhasil!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider">
                                <th class="p-4 border-b font-bold w-24">Foto</th>
                                <th class="p-4 border-b font-bold">Nama Ruangan</th>
                                <th class="p-4 border-b font-bold text-center">Kapasitas</th>
                                <th class="p-4 border-b font-bold text-center">Status</th>
                                <th class="p-4 border-b font-bold text-center w-28">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 divide-y divide-gray-100">
                            @forelse ($rooms as $room)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="p-4">
                                        @if($room->image)
                                            <img src="{{ asset('storage/' . $room->image) }}" alt="{{ $room->name }}" class="w-16 h-16 object-cover rounded-lg shadow-sm border border-gray-200">
                                        @else
                                            <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center text-gray-400 border border-gray-300 border-dashed">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="p-4">
                                        <div class="font-bold text-gray-900 text-lg">{{ $room->name }}</div>
                                        <div class="text-sm text-gray-500 line-clamp-1 mt-1">{{ $room->description ?: 'Tidak ada deskripsi.' }}</div>
                                    </td>
                                    <td class="p-4 text-center">
                                        <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-sm font-semibold border border-blue-100">
                                            {{ $room->capacity }} Orang
                                        </span>
                                    </td>
                                    <td class="p-4 text-center">
                                        @if($room->is_active)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Tersedia</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Renovasi</span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-center">
                                        <a href="{{ route('rooms.edit', $room->id) }}" class="inline-flex items-center px-3 py-1.5 bg-yellow-50 text-yellow-700 hover:bg-yellow-100 font-semibold rounded-md text-sm border border-yellow-200 transition-colors">
                                            Edit
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-gray-500">Belum ada data ruangan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>