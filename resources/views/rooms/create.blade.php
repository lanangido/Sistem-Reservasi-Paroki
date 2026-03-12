<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Ruangan Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 p-6 sm:p-8">
                
                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg text-sm">
                        <strong class="font-bold">Mohon periksa kembali isian Anda:</strong>
                        <ul class="mt-2 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('rooms.store') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-5">
                        <label for="name" class="block text-sm font-bold text-gray-700 mb-2">Nama Ruangan / Aula *</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" class="shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md w-full" required placeholder="Contoh: Aula Paroki Lt. 2">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label for="capacity" class="block text-sm font-bold text-gray-700 mb-2">Kapasitas Maksimal (Orang) *</label>
                            <input type="number" name="capacity" id="capacity" value="{{ old('capacity') }}" min="1" class="shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md w-full" required placeholder="Contoh: 150">
                        </div>

                        <div>
                            <label for="is_active" class="block text-sm font-bold text-gray-700 mb-2">Status Saat Ini *</label>
                            <select name="is_active" id="is_active" class="shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md w-full" required>
                                <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>Tersedia (Bisa dipinjam)</option>
                                <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Sedang Renovasi (Tidak bisa dipinjam)</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-5">
                        <label for="image" class="block text-sm font-bold text-gray-700 mb-2">Foto / Gambar Ruangan (Opsional)</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md bg-gray-50 hover:bg-gray-100 transition-colors">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600 justify-center">
                                    <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500 px-2 py-1">
                                        <span>Pilih File Gambar</span>
                                        <input id="image" name="image" type="file" class="sr-only" accept="image/jpeg, image/png, image/jpg">
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, JPEG maksimal 2MB</p>
                            </div>
                        </div>
                        <p id="filename" class="text-center text-xs text-indigo-600 mt-2 font-semibold"></p>
                    </div>

                    <div class="mb-6">
                        <label for="description" class="block text-sm font-bold text-gray-700 mb-2">Fasilitas / Deskripsi Singkat</label>
                        <textarea name="description" id="description" rows="3" class="shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md w-full" placeholder="Contoh: AC, Proyektor, Sound System, dan 100 Kursi Lipat">{{ old('description') }}</textarea>
                    </div>

                    <div class="flex items-center justify-end border-t border-gray-100 pt-5 mt-5">
                        <a href="{{ route('rooms.index') }}" class="text-gray-500 hover:text-gray-800 mr-4 font-semibold text-sm transition-colors">Batal</a>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-lg transition-colors shadow-sm">
                            Simpan Ruangan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        // Script sederhana agar admin tahu file sudah terpilih
        document.getElementById('image').onchange = function () {
            document.getElementById('filename').textContent = "File terpilih: " + this.files[0].name;
        };
    </script>
</x-app-layout>