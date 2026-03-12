<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Akun Pengguna') }}
            </h2>
            <a href="{{ route('users.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-5 rounded-xl transition-all shadow-md hover:-translate-y-0.5 text-sm flex items-center gap-2">
                + Tambah Akun Baru
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
            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-100 text-gray-700 text-sm uppercase">
                                    <th class="p-4 border-b">Nama & Email</th>
                                    <th class="p-4 border-b">No. WhatsApp</th>
                                    <th class="p-4 border-b">Lingkungan</th>
                                    <th class="p-4 border-b text-center">Hak Akses (Role)</th>
                                    <th class="p-4 border-b text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr class="hover:bg-gray-50 text-sm">
                                        <td class="p-4 border-b">
                                            <div class="font-bold text-gray-800">{{ $user->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                        </td>
                                        <td class="p-4 border-b">{{ $user->phone_number ?? '-' }}</td>
                                        <td class="p-4 border-b">{{ $user->lingkungan ?? '-' }}</td>
                                        <td class="p-4 border-b text-center">
                                            @if($user->role == 'admin')
                                                <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-bold uppercase">Admin</span>
                                            @elseif($user->role == 'sekretariat')
                                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-bold uppercase">Sekretariat</span>
                                            @else
                                                <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-bold uppercase">Umat</span>
                                            @endif
                                        </td>
                                        <td class="p-4 border-b text-center flex justify-center gap-2">
                                            <a href="{{ route('users.edit', $user->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-bold py-1 px-3 rounded">Edit</a>
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus akun ini? Segala data riwayat peminjaman akun ini juga akan terhapus.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white text-xs font-bold py-1 px-3 rounded" {{ auth()->id() == $user->id ? 'disabled' : '' }}>Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 