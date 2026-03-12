<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Ruangan: <span class="text-indigo-600">{{ $room->name }}</span>
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

                <form method="POST" action="{{ route('rooms.update', $room->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    
                    <div class="mb-5">
                        <label for="name" class="block text-sm font-bold text-gray-700 mb-2">Nama Ruangan / Aula *</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $room->name) }}" class="shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md w-full" required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label for="capacity" class="block text-sm font-bold text-gray-700 mb-2">Kapasitas Maksimal (Orang) *</label>
                            <input type="number" name="capacity" id="capacity" value="{{ old('capacity', $room->capacity) }}" min="1" class="shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md w-full" required>
                        </div>

                        <div>
                            <label for="is_active" class="block text-sm font-bold text-gray-700 mb-2">Status Saat Ini *</label>
                            <select name="is_active" id="is_active" class="shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md w-full" required>
                                <option value="1" {{ old('is_active', $room->is_active) == '1' ? 'selected' : '' }}>Tersedia (Bisa dipinjam)</option>
                                <option value="0" {{ old('is_active', $room->is_active) == '0' ? 'selected' : '' }}>Sedang Renovasi</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Foto Saat Ini</label>
                        @if($room->image)
                            <div class="mb-3">
                                <img src="{{ asset('storage/' . $room->image) }}" alt="Foto {{ $room->name }}" class="h-40 rounded-lg shadow-sm border border-gray-200 object-cover">
                            </div>
                            <p class="text-xs text-gray-500 mb-2">Unggah file baru di bawah ini jika ingin mengganti foto saat ini.</p>
                        @else
                            <div class="mb-3 text-sm text-gray-500 italic">Belum ada foto yang diunggah untuk ruangan ini.</div>
                        @endif

                        <input id="image" name="image" type="file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" accept="image/jpeg, image/png, image/jpg">
                        <p class="text-xs text-gray-400 mt-1">Format: PNG, JPG maksimal 2MB.</p>
                    </div>

                    <div class="mb-6">
                        <label for="description" class="block text-sm font-bold text-gray-700 mb-2">Fasilitas / Deskripsi Singkat</label>
                        <textarea name="description" id="description" rows="3" class="shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md w-full">{{ old('description', $room->description) }}</textarea>
                    </div>

                    <div class="flex items-center justify-between border-t border-gray-100 pt-5 mt-5">
                        <a href="{{ route('rooms.index') }}" class="text-gray-500 hover:text-gray-800 font-semibold text-sm transition-colors">Kembali ke Daftar</a>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-lg transition-colors shadow-sm">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>