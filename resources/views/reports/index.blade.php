<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan & Rekapitulasi Peminjaman') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-8">
<div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-8">
                <form method="GET" action="{{ route('reports.index') }}" class="flex flex-col md:flex-row items-end gap-4">
                    
                    <div class="flex gap-4 w-full md:w-auto">
                        <div>
                            <label for="month" class="block text-sm font-bold text-gray-700 mb-2">Bulan</label>
                            <select name="month" id="month" class="rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 w-full sm:w-auto">
                                @for($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label for="year" class="block text-sm font-bold text-gray-700 mb-2">Tahun</label>
                            <select name="year" id="year" class="rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 w-full sm:w-auto">
                                @for($y = date('Y') - 2; $y <= date('Y') + 1; $y++)
                                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div class="w-full md:flex-1">
                        <label for="search" class="block text-sm font-bold text-gray-700 mb-2">Cari Lingkungan / Peminjam</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input type="text" name="search" id="search" value="{{ $search }}" placeholder="Ketik nama lingkungan atau orang..." class="pl-10 appearance-none rounded-xl relative block w-full border border-gray-300 placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                        </div>
                    </div>

                    <div class="flex gap-3 w-full md:w-auto justify-end mt-4 md:mt-0">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-xl transition-colors shadow-sm whitespace-nowrap">
                            Filter Data
                        </button>
                        
                        <a href="{{ route('reports.export', ['month' => $month, 'year' => $year, 'search' => $search]) }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-6 rounded-xl transition-colors shadow-sm flex items-center whitespace-nowrap">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Unduh CSV
                        </a>
                    </div>
                </form>
            </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 border-l-4 border-indigo-500">
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-wide">Total Pengajuan</p>
                    <p class="text-4xl font-extrabold text-gray-900 mt-2">{{ $totalBookings }}</p>
                </div>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 border-l-4 border-green-500">
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-wide">Disetujui</p>
                    <p class="text-4xl font-extrabold text-green-600 mt-2">{{ $approvedBookings }}</p>
                </div>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 border-l-4 border-red-500">
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-wide">Ditolak</p>
                    <p class="text-4xl font-extrabold text-red-600 mt-2">{{ $rejectedBookings }}</p>
                </div>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 border-l-4 border-yellow-500">
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-wide">Menunggu Review</p>
                    <p class="text-4xl font-extrabold text-yellow-600 mt-2">{{ $pendingBookings }}</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-900">Rincian Kegiatan (Bulan {{ \Carbon\Carbon::create()->month($month)->translatedFormat('F') }} {{ $year }})</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white text-gray-500 text-xs uppercase tracking-wider">
                                <th class="p-4 border-b font-bold">Tanggal</th>
                                <th class="p-4 border-b font-bold">Ruangan</th>
                                <th class="p-4 border-b font-bold">Peminjam / Lingkungan</th>
                                <th class="p-4 border-b font-bold">Kegiatan</th>
                                <th class="p-4 border-b font-bold text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 divide-y divide-gray-100">
                            @forelse ($bookings as $booking)
                                <tr class="hover:bg-gray-50">
                                    <td class="p-4 whitespace-nowrap">
                                        <div class="font-bold text-gray-900">{{ \Carbon\Carbon::parse($booking->start_time)->format('d/m/Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} WIB</div>
                                    </td>
                                    <td class="p-4 font-semibold text-indigo-700">{{ $booking->room->name }}</td>
                                    <td class="p-4">
                                        <div class="font-bold">{{ $booking->user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $booking->user->lingkungan ?? '-' }}</div>
                                    </td>
                                    <td class="p-4 text-sm">{{ $booking->purpose }}</td>
                                    <td class="p-4 text-center">
                                        @if($booking->status == 'approved')
                                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold">Disetujui</span>
                                        @elseif($booking->status == 'rejected')
                                            <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-bold">Ditolak</span>
                                        @else
                                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-bold">Menunggu</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-gray-500 font-medium">Tidak ada data peminjaman di bulan ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>