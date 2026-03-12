<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Paroki Santo Paulus Miki') }}
        </h2>
    </x-slot>

    <div class="py-12">
        @if (session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Puji Tuhan!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Perhatian:</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif
        
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-xl shadow-lg overflow-hidden mb-8">
                <div class="p-6 sm:p-8 text-white flex justify-between items-center">
                    <div>
                        <h3 class="text-2xl sm:text-3xl font-bold mb-2">Selamat datang, {{ Auth::user()->name }}!</h3>
                        <p class="text-blue-100 text-lg">Anda masuk sebagai: <span
                                class="uppercase font-extrabold text-yellow-300 tracking-wider">{{ Auth::user()->role }}</span>
                        </p>
                    </div>
                    <div class="hidden sm:block text-6xl opacity-80">
                        ⛪
                    </div>
                </div>
            </div>

            <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-end border-b border-gray-200 pb-3 gap-4">
                <div>
                    <h4 class="text-2xl font-bold text-gray-800">Fasilitas & Aset Paroki</h4>
                    <span class="text-sm text-gray-500">Pilih ruangan spesifik di bawah ini atau ajukan peminjaman umum</span>
                </div>
                
                @if(Auth::user()->role == 'umat')
                    <a href="{{ route('bookings.create') }}" class="inline-flex items-center bg-indigo-600 text-white px-5 py-2.5 rounded-lg hover:bg-indigo-700 font-bold transition-all shadow-md text-sm hover:-translate-y-0.5">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Buat Pengajuan Peminjaman
                    </a>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($rooms as $room)
                    <div
                        class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 flex flex-col">
                        <a href="{{ route('rooms.show', $room->id) }}"
                            class="block h-48 bg-gray-100 overflow-hidden relative group">
                            @if($room->image)
                                <img src="{{ asset('storage/' . $room->image) }}" alt="{{ $room->name }}"
                                    class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <svg class="w-12 h-12 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                            @endif
                        </a>
                        <div class="p-6 flex-grow">
                            <div class="flex justify-between items-start mb-4">
                                <a href="{{ route('rooms.show', $room->id) }}"
                                    class="hover:text-indigo-600 transition-colors">
                                    <h5 class="text-xl font-bold text-gray-900 leading-tight">{{ $room->name }}</h5>
                                </a>
                                @if($room->is_active)
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-200">
                                        Tersedia
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-200">
                                        Renovasi
                                    </span>
                                @endif
                            </div>

                            <div class="flex items-center text-sm text-gray-600 mb-4 bg-gray-50 p-2 rounded-lg">
                                <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                                Kapasitas: <span class="font-bold ml-1 text-gray-900">{{ $room->capacity }} Orang</span>
                            </div>

                            <p class="text-sm text-gray-500 line-clamp-2">
                                {{ $room->description }}
                            </p>
                        </div>
                        
                        <div class="p-4 bg-gray-50 border-t border-gray-100">
                            @if(Auth::user()->role == 'umat' && $room->is_active)
                                <a href="{{ route('bookings.create', ['room_id' => $room->id]) }}"
                                    class="block text-center w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-4 rounded-lg transition-colors text-sm shadow-sm">
                                    Ajukan Peminjaman
                                </a>
                            @elseif(Auth::user()->role == 'sekretariat')
                                <button
                                    class="w-full bg-indigo-100 text-indigo-700 hover:bg-indigo-200 font-bold py-2.5 px-4 rounded-lg transition-colors text-sm">
                                    Kelola Jadwal Ruangan
                                </button>
                            @else
                                <button
                                    class="w-full bg-gray-200 text-gray-500 font-bold py-2.5 px-4 rounded-lg cursor-not-allowed text-sm"
                                    disabled>
                                    Tidak Tersedia
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            @if(Auth::user()->role == 'umat')
                <div class="mt-12 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                        <h4 class="text-xl font-bold text-gray-800">Riwayat Pengajuan Saya</h4>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-100 text-gray-600 text-sm uppercase tracking-wider">
                                    <th class="p-4 border-b font-bold">Fasilitas & Aset</th>
                                    <th class="p-4 border-b font-bold">Tujuan Kegiatan</th>
                                    <th class="p-4 border-b font-bold">Waktu Pelaksanaan</th>
                                    <th class="p-4 border-b font-bold text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 divide-y divide-gray-100">
                                @forelse ($myBookings as $booking)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="p-4">
                                            @if($booking->room)
                                                <div class="font-semibold text-gray-900">{{ $booking->room->name }}</div>
                                            @else
                                                <div class="font-semibold text-indigo-600 italic">Hanya Peminjaman Aset</div>
                                            @endif
                                            
                                            @if($booking->assets->count() > 0)
                                                <div class="mt-2 flex flex-wrap gap-1">
                                                    @foreach($booking->assets as $asset)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-200 text-gray-800">
                                                            {{ $asset->asset_name }} ({{ $asset->pivot->quantity }}x)
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </td>
                                        <td class="p-4">
                                            {{ $booking->purpose }} <br>
                                            <span class="text-xs text-gray-500 font-medium">{{ $booking->attendees }} Peserta</span>
                                        </td>
                                        <td class="p-4 text-sm whitespace-nowrap">
                                            <span class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($booking->start_time)->translatedFormat('d M Y') }}</span><br>
                                            <span class="text-gray-500">{{ \Carbon\Carbon::parse($booking->start_time)->translatedFormat('H:i') }}
                                                - {{ \Carbon\Carbon::parse($booking->end_time)->translatedFormat('H:i') }} WIB</span>
                                        </td>
                                        <td class="p-4 text-center">
                                            @if($booking->status == 'pending')
                                                <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-bold border border-yellow-200">⏳ Menunggu</span>
                                            @elseif($booking->status == 'approved')
                                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-bold border border-green-200">✅ Disetujui</span>
                                            @else
                                                <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-bold border border-red-200">❌ Ditolak</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="p-8 text-center text-gray-500 bg-gray-50">
                                            <div class="flex flex-col items-center justify-center">
                                                <span class="text-4xl mb-3 opacity-50">📂</span>
                                                <p>Belum ada riwayat pengajuan peminjaman.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            @if(Auth::user()->role == 'sekretariat' || Auth::user()->role == 'admin')
                
                <div class="mt-12 bg-white rounded-2xl shadow-sm border border-indigo-100 overflow-hidden">
                    <div class="p-6 border-b border-indigo-100 flex justify-between items-center bg-indigo-50">
                        <h4 class="text-xl font-bold text-indigo-900">Antrean Menunggu Review</h4>
                        <span class="bg-indigo-600 text-white text-xs font-bold px-3 py-1 rounded-full">{{ $pendingBookings->count() }} Permintaan</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-white text-gray-500 text-xs uppercase tracking-wider">
                                    <th class="p-4 border-b font-bold w-1/4">Pemohon</th>
                                    <th class="p-4 border-b font-bold w-2/4">Fasilitas, Aset & Waktu</th>
                                    <th class="p-4 border-b font-bold text-center w-1/4">Aksi (Validasi)</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 divide-y divide-gray-100">
                                @forelse ($pendingBookings as $booking)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="p-4 align-top">
                                            <div class="font-bold text-gray-900">{{ $booking->user->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $booking->user->lingkungan ?? 'Umat' }}</div>
                                            <div class="text-xs text-blue-600 mt-1">{{ $booking->purpose }} ({{ $booking->attendees }} Orang)</div>
                                        </td>
                                        <td class="p-4 align-top">
                                            @if($booking->room)
                                                <div class="font-bold text-indigo-700">{{ $booking->room->name }}</div>
                                            @else
                                                <div class="font-bold text-indigo-500 italic">Hanya Peminjaman Aset</div>
                                            @endif
                                            
                                            <div class="text-sm text-gray-800 mt-1">
                                                {{ \Carbon\Carbon::parse($booking->start_time)->translatedFormat('d M Y') }}
                                            </div>
                                            <div class="text-xs font-semibold text-gray-500">
                                                {{ \Carbon\Carbon::parse($booking->start_time)->translatedFormat('H:i') }} -
                                                {{ \Carbon\Carbon::parse($booking->end_time)->translatedFormat('H:i') }} WIB
                                            </div>

                                            @if($booking->assets->count() > 0)
                                                <div class="mt-2 text-xs text-gray-600 border-t border-gray-100 pt-2">
                                                    <strong class="text-gray-800">Aset Tambahan:</strong>
                                                    <ul class="list-disc list-inside mt-1">
                                                        @foreach($booking->assets as $asset)
                                                            <li>{{ $asset->asset_name }} ({{ $asset->pivot->quantity }} unit)</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                        </td>
                                        
                                        <td class="p-4 align-middle">
                                            <form method="POST" class="flex flex-col gap-2">
                                                @csrf
                                                
                                                <input type="text" name="admin_note" placeholder="Catatan/Alasan (Opsional)..."
                                                    class="text-xs border-gray-300 rounded shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 w-full mb-1">

                                                <div class="flex gap-2 justify-center">
                                                    <button type="submit" formaction="{{ route('booking.approve', $booking->id) }}"
                                                        class="flex-1 bg-green-600 hover:bg-green-700 text-white text-xs font-bold py-2 px-3 rounded transition-colors text-center cursor-pointer shadow-sm"
                                                        onclick="return confirm('Setujui jadwal ini dan kirim Notifikasi WA?')">
                                                        Setujui
                                                    </button>
                                                    
                                                    <button type="submit" formaction="{{ route('booking.reject', $booking->id) }}"
                                                        class="flex-1 bg-red-600 hover:bg-red-700 text-white text-xs font-bold py-2 px-3 rounded transition-colors text-center cursor-pointer shadow-sm"
                                                        onclick="return confirm('Yakin ingin menolak jadwal ini dan kirim WA?')">
                                                        Tolak
                                                    </button>
                                                </div>
                                            </form>
                                        </td>
                                        </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="p-8 text-center text-gray-500 bg-gray-50">
                                            <span class="text-4xl mb-3 opacity-50">☕</span>
                                            <p>Belum ada antrean pengajuan yang perlu di-review.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-8 bg-white rounded-2xl shadow-sm border border-emerald-100 overflow-hidden">
                    <div class="p-6 border-b border-emerald-100 flex justify-between items-center bg-emerald-50">
                        <h4 class="text-xl font-bold text-emerald-900">Jadwal Kegiatan Mendatang (Disetujui)</h4>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-white text-gray-500 text-xs uppercase tracking-wider">
                                    <th class="p-4 border-b font-bold w-1/4">Tanggal & Waktu</th>
                                    <th class="p-4 border-b font-bold w-1/4">Fasilitas / Ruangan</th>
                                    <th class="p-4 border-b font-bold w-1/3">Kegiatan & Penanggung Jawab</th>
                                    <th class="p-4 border-b font-bold text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 divide-y divide-gray-100">
                                @forelse ($upcomingBookings as $booking)
                                    <tr class="hover:bg-emerald-50/50 transition-colors">
                                        <td class="p-4 align-top">
                                            <div class="font-bold text-emerald-700 text-base">
                                                {{ \Carbon\Carbon::parse($booking->start_time)->translatedFormat('l, d M Y') }}
                                            </div>
                                            <div class="text-sm font-semibold text-gray-600 mt-1 flex items-center">
                                                <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                {{ \Carbon\Carbon::parse($booking->start_time)->translatedFormat('H:i') }} -
                                                {{ \Carbon\Carbon::parse($booking->end_time)->translatedFormat('H:i') }} WIB
                                            </div>
                                        </td>
                                        <td class="p-4 align-top">
                                            @if($booking->room)
                                                <div class="font-bold text-gray-900">{{ $booking->room->name }}</div>
                                                <div class="text-xs text-gray-500 mt-1">Kapasitas: {{ $booking->room->capacity }} Orang</div>
                                            @else
                                                <div class="font-bold text-indigo-500 italic">Peminjaman Aset Saja</div>
                                            @endif
                                            
                                            @if($booking->assets->count() > 0)
                                                <div class="mt-2 flex flex-wrap gap-1">
                                                    @foreach($booking->assets as $asset)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-100">
                                                            {{ $asset->asset_name }} ({{ $asset->pivot->quantity }})
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </td>
                                        <td class="p-4 align-top">
                                            <div class="font-bold text-gray-800">{{ $booking->purpose }}</div>
                                            <div class="text-sm text-gray-600 mt-1">
                                                PJ: <span class="font-semibold">{{ $booking->user->name }}</span>
                                                ({{ $booking->user->lingkungan ?? 'Umat' }})
                                            </div>
                                            <div class="text-xs text-emerald-600 font-medium mt-1">{{ $booking->attendees }} Peserta Terjadwal</div>
                                        </td>
                                        <td class="p-4 text-center align-top">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-200 shadow-sm">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                Disetujui
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="p-8 text-center text-gray-500 bg-gray-50">
                                            <div class="flex flex-col items-center justify-center">
                                                <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <p class="font-medium">Tidak ada jadwal kegiatan yang akan datang.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>