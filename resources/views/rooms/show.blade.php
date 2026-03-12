<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <a href="{{ route('dashboard') }}" class="text-indigo-600 hover:text-indigo-800 transition-colors">&larr; Kembali</a>
            <span class="text-gray-300">|</span>
            Detail Ruangan
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-2xl border border-gray-100 flex flex-col md:flex-row">
                
                <div class="md:w-1/2 bg-gray-50 flex items-center justify-center border-b md:border-b-0 md:border-r border-gray-100">
                    @if($room->image)
                        <img src="{{ asset('storage/' . $room->image) }}" alt="{{ $room->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="py-32 flex flex-col items-center justify-center text-gray-400">
                            <svg class="w-16 h-16 mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="text-sm font-medium">Belum ada foto ruangan</span>
                        </div>
                    @endif
                </div>

                <div class="md:w-1/2 p-8 lg:p-12 flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-3xl font-extrabold text-gray-900 leading-tight">{{ $room->name }}</h3>
                            @if($room->is_active)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-200">Tersedia</span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-200">Renovasi</span>
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

                    <div class="pt-6 border-t border-gray-100 mt-auto">
                        @if(Auth::user()->role == 'umat' && $room->is_active)
                            <a href="{{ route('booking.create', $room->id) }}" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-4 rounded-xl transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                Lanjutkan ke Peminjaman
                            </a>
                        @elseif(Auth::user()->role == 'sekretariat' || Auth::user()->role == 'admin')
                            <a href="{{ route('rooms.edit', $room->id) }}" class="block w-full text-center bg-yellow-100 text-yellow-800 hover:bg-yellow-200 font-bold py-3.5 px-4 rounded-xl transition-colors border border-yellow-300">
                                Edit Data Ruangan Ini
                            </a>
                        @else
                            <button class="w-full bg-gray-200 text-gray-500 font-bold py-3.5 px-4 rounded-xl cursor-not-allowed" disabled>
                                Saat ini tidak dapat dipinjam
                            </button>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>