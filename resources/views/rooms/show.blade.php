<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <a href="{{ route('dashboard') }}" class="text-indigo-600 hover:text-indigo-800 transition-colors">&larr; Kembali</a>
            <span class="text-gray-300">|</span>
            Detail & Jadwal Ruangan
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 flex flex-col md:flex-row">
                <div class="md:w-1/2 bg-gray-50 flex items-center justify-center border-b md:border-b-0 md:border-r border-gray-100 h-64 md:h-auto">
                    @if($room->image)
                        <img src="{{ asset('storage/' . $room->image) }}" alt="{{ $room->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="flex flex-col items-center justify-center text-gray-400 p-12">
                            <svg class="w-16 h-16 mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="text-sm font-medium">Belum ada foto ruangan</span>
                        </div>
                    @endif
                </div>

                <div class="md:w-1/2 p-8 lg:p-10 flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-start mb-4 gap-4">
                            <h3 class="text-3xl font-extrabold text-gray-900 leading-tight">{{ $room->name }}</h3>
                            @if($room->is_active)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-200 whitespace-nowrap">Tersedia</span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-200 whitespace-nowrap">Renovasi</span>
                            @endif
                        </div>

                        <div class="flex items-center text-sm text-gray-700 mb-6 bg-indigo-50 p-3 rounded-lg border border-indigo-100 inline-block">
                            <svg class="w-5 h-5 mr-2 text-indigo-600 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Kapasitas Maksimal: <span class="font-bold ml-1">{{ $room->capacity }} Orang</span>
                        </div>

                        <div class="prose prose-sm text-gray-600 mb-8">
                            <h4 class="text-gray-900 font-bold mb-2 uppercase tracking-wide text-xs">Fasilitas & Deskripsi</h4>
                            <p class="whitespace-pre-line leading-relaxed">{{ $room->description ?: 'Belum ada deskripsi yang ditambahkan oleh pengurus.' }}</p>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-100 mt-auto flex flex-col sm:flex-row gap-3">
                        @if($room->is_active)
                            <a href="{{ route('bookings.create', ['room_id' => $room->id]) }}" class="flex-1 text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl transition-all duration-300 shadow-sm">
                                Ajukan Peminjaman
                            </a>
                        @else
                            <button class="flex-1 bg-gray-200 text-gray-500 font-bold py-3 px-4 rounded-xl cursor-not-allowed" disabled>
                                Tidak Dapat Dipinjam
                            </button>
                        @endif

                        @if(Auth::user()->role == 'sekretariat' || Auth::user()->role == 'admin')
                            <a href="{{ route('rooms.edit', $room->id) }}" class="flex-1 text-center bg-yellow-100 text-yellow-800 hover:bg-yellow-200 font-bold py-3 px-4 rounded-xl transition-colors border border-yellow-300">
                                Edit / Ubah Status Ruangan
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Kalender Kegiatan & Reservasi</h3>
                        <p class="text-sm text-gray-500 mt-1">Daftar jadwal yang sudah <span class="text-green-600 font-semibold">disetujui</span> untuk ruangan ini.</p>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-white text-gray-500 text-xs uppercase tracking-wider border-b-2 border-gray-200">
                                    <th class="p-4 font-bold w-1/4">Tanggal</th>
                                    <th class="p-4 font-bold w-1/4">Jam Penggunaan</th>
                                    <th class="p-4 font-bold w-1/3">Nama Kegiatan</th>
                                    <th class="p-4 font-bold">Penanggung Jawab</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 divide-y divide-gray-100">
                                @forelse ($upcomingBookings as $booking)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="p-4">
                                            <div class="font-bold text-gray-900">
                                                {{ \Carbon\Carbon::parse($booking->start_time)->translatedFormat('l, d M Y') }}
                                            </div>
                                        </td>
                                        <td class="p-4">
                                            <div class="inline-flex items-center px-3 py-1 rounded bg-indigo-50 text-indigo-700 font-semibold text-sm border border-indigo-100">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - 
                                                {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}
                                            </div>
                                        </td>
                                        <td class="p-4">
                                            <div class="font-bold text-gray-800">{{ $booking->purpose }}</div>
                                            <div class="text-xs text-gray-500 mt-1">{{ $booking->attendees }} Orang</div>
                                        </td>
                                        <td class="p-4">
                                            <div class="font-semibold text-gray-700">{{ $booking->user->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $booking->user->lingkungan ?? 'Umat Umum' }}</div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="p-12 text-center">
                                            <div class="flex flex-col items-center justify-center text-gray-400">
                                                <svg class="w-12 h-12 mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                <p class="font-medium text-gray-500 text-lg">Belum ada jadwal tersimpan.</p>
                                                <p class="text-sm mt-1">Ruangan ini kosong dan siap dipesan.</p>
                                            </div>
                                        </td>
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