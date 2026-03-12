<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan & Rekapitulasi Peminjaman') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
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

            @if(isset($chartRoomData) && isset($chartPurposeData))
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-sm font-bold text-gray-800 mb-4 uppercase tracking-wide text-center">Intensitas Penggunaan Fasilitas</h3>
                        <div class="relative h-64 w-full">
                            <canvas id="roomChart"></canvas>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-sm font-bold text-gray-800 mb-4 uppercase tracking-wide text-center">Distribusi Jenis Kegiatan</h3>
                        <div class="relative h-64 w-full">
                            <canvas id="purposeChart"></canvas>
                        </div>
                    </div>
                </div>

                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const roomLabels = {!! json_encode($chartRoomData->keys()) !!};
                        const roomData = {!! json_encode($chartRoomData->values()) !!};
                        
                        const purposeLabels = {!! json_encode($chartPurposeData->keys()) !!};
                        const purposeData = {!! json_encode($chartPurposeData->values()) !!};

                        // Konfigurasi Chart Ruangan (Bar Chart)
                        new Chart(document.getElementById('roomChart'), {
                            type: 'bar',
                            data: {
                                labels: roomLabels.length > 0 ? roomLabels : ['Tidak ada data'],
                                datasets: [{
                                    label: 'Jumlah Kegiatan',
                                    data: roomData.length > 0 ? roomData : [0],
                                    backgroundColor: 'rgba(79, 70, 229, 0.7)',
                                    borderColor: 'rgb(79, 70, 229)',
                                    borderWidth: 1,
                                    borderRadius: 4
                                }]
                            },
                            options: { 
                                maintainAspectRatio: false, 
                                plugins: { legend: { display: false } },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            precision: 0 // Pastikan sumbu Y menampilkan angka bulat
                                        }
                                    }
                                }
                            }
                        });

                        // Konfigurasi Chart Kegiatan (Doughnut Chart)
                        new Chart(document.getElementById('purposeChart'), {
                            type: 'doughnut',
                            data: {
                                labels: purposeLabels.length > 0 ? purposeLabels : ['Tidak ada data'],
                                datasets: [{
                                    data: purposeData.length > 0 ? purposeData : [1], // Isi 1 agar bentuk donat terlihat, warnanya kita buat abu-abu di bawah
                                    backgroundColor: purposeData.length > 0 ? [
                                        'rgba(16, 185, 129, 0.7)',
                                        'rgba(245, 158, 11, 0.7)',
                                        'rgba(59, 130, 246, 0.7)',
                                        'rgba(236, 72, 153, 0.7)',
                                        'rgba(139, 92, 246, 0.7)'
                                    ] : ['rgba(229, 231, 235, 1)'], // Warna abu-abu untuk data kosong
                                    borderWidth: 0
                                }]
                            },
                            options: { 
                                maintainAspectRatio: false,
                                plugins: {
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                // Matikan tooltip jika tidak ada data
                                                return purposeData.length > 0 ? context.label + ': ' + context.raw : 'Belum ada kegiatan';
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    });
                </script>
            @endif
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-900">Rincian Kegiatan (Bulan {{ \Carbon\Carbon::create()->month($month)->translatedFormat('F') }} {{ $year }})</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white text-gray-500 text-xs uppercase tracking-wider">
                                <th class="p-4 border-b font-bold">Tanggal</th>
                                <th class="p-4 border-b font-bold">Fasilitas & Aset</th>
                                <th class="p-4 border-b font-bold">Peminjam / Lingkungan</th>
                                <th class="p-4 border-b font-bold">Kegiatan</th>
                                <th class="p-4 border-b font-bold text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 divide-y divide-gray-100">
                            @forelse ($bookings as $booking)
                                <tr class="hover:bg-gray-50">
                                    <td class="p-4 whitespace-nowrap align-top">
                                        <div class="font-bold text-gray-900">{{ \Carbon\Carbon::parse($booking->start_time)->format('d/m/Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} WIB</div>
                                    </td>
                                    <td class="p-4 align-top">
                                        @if($booking->room)
                                            <div class="font-semibold text-indigo-700">{{ $booking->room->name }}</div>
                                        @else
                                            <div class="font-semibold text-gray-500 italic">Peminjaman Aset Saja</div>
                                        @endif

                                        @if($booking->assets->count() > 0)
                                            <div class="mt-1">
                                                <ul class="list-disc list-inside text-xs text-gray-600">
                                                    @foreach($booking->assets as $asset)
                                                        <li>{{ $asset->asset_name }} ({{ $asset->pivot->quantity }}x)</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="p-4 align-top">
                                        <div class="font-bold">{{ $booking->user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $booking->user->lingkungan ?? '-' }}</div>
                                    </td>
                                    <td class="p-4 text-sm align-top">{{ $booking->purpose }}</td>
                                    <td class="p-4 text-center align-top">
                                        @if($booking->status == 'approved')
                                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold shadow-sm">Disetujui</span>
                                        @elseif($booking->status == 'rejected')
                                            <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-bold shadow-sm">Ditolak</span>
                                        @else
                                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-bold shadow-sm">Menunggu</span>
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