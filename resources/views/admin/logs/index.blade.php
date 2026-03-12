<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Audit Trail: Riwayat Aktivitas Sistem') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <p class="text-sm text-gray-500 mb-4">Catatan seluruh aktivitas persetujuan, penolakan, dan penyelesaian kegiatan oleh Admin/Sekretariat untuk keperluan akuntabilitas.</p>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-100 text-gray-700 text-xs uppercase">
                                    <th class="p-4 border-b">Waktu Kejadian</th>
                                    <th class="p-4 border-b">Aktor (Admin)</th>
                                    <th class="p-4 border-b">Aksi Dilakukan</th>
                                    <th class="p-4 border-b">Target Pengajuan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $log)
                                    <tr class="hover:bg-gray-50 text-sm">
                                        <td class="p-4 border-b font-mono text-gray-600">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                                        <td class="p-4 border-b font-bold text-gray-800">{{ $log->user->name ?? 'Sistem' }}</td>
                                        <td class="p-4 border-b">
                                            @if($log->action == 'approved')
                                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-bold">Menyetujui</span>
                                            @elseif($log->action == 'rejected')
                                                <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-bold">Menolak</span>
                                            @elseif($log->action == 'completed')
                                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-bold">Menyelesaikan (Aset Kembali)</span>
                                            @else
                                                <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs font-bold">{{ $log->action }}</span>
                                            @endif
                                        </td>
                                        <td class="p-4 border-b text-gray-600">
                                            Pengajuan milik <strong>{{ $log->booking->user->name ?? 'User Terhapus' }}</strong> <br>
                                            <span class="text-xs">Tujuan: {{ $log->booking->purpose ?? '-' }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="p-6 text-center text-gray-500">Belum ada riwayat aktivitas yang tercatat.</td>
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