<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Akun Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    
                    @if($errors->any())
                        <div class="mb-4 bg-red-50 text-red-700 p-4 rounded border border-red-200">
                            <p class="font-bold mb-1">Terdapat kesalahan:</p>
                            <ul class="list-disc list-inside text-sm">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf
                        
                        <div class="grid grid-cols-1 gap-6">
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Masukkan nama lengkap">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="contoh@email.com">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">No. WhatsApp</label>
                                    <input type="text" name="phone_number" value="{{ old('phone_number') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Contoh: 08123456789">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Lingkungan</label>
                                    <input type="text" name="lingkungan" value="{{ old('lingkungan') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Opsional / Kosongkan jika bukan umat">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Hak Akses (Role) <span class="text-red-500">*</span></label>
                                <select name="role" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="umat" {{ old('role') == 'umat' ? 'selected' : '' }}>Umat</option>
                                    <option value="sekretariat" {{ old('role') == 'sekretariat' ? 'selected' : '' }}>Sekretariat</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                                <p class="text-xs text-gray-500 mt-1">Tentukan hak akses pengguna ini di dalam sistem.</p>
                            </div>

                            <div class="border-t border-gray-200 pt-4 mt-2">
                                <h3 class="text-md font-bold text-gray-800 mb-3">Setup Password</h3>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                                        <input type="password" name="password" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Minimal 8 karakter">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password <span class="text-red-500">*</span></label>
                                        <input type="password" name="password_confirmation" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Ketik ulang password">
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="mt-8 flex justify-end gap-3">
                            <a href="{{ route('users.index') }}" class="bg-gray-200 text-gray-800 px-4 py-2 rounded shadow-sm hover:bg-gray-300 font-bold">Batal</a>
                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded shadow-sm hover:bg-indigo-700 font-bold">Daftarkan Akun</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>