<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Formulir Peminjaman Fasilitas & Aset') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-gray-900">
                    
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-md">
                            <p class="font-bold mb-1">Terdapat kesalahan dalam pengisian form:</p>
                            <ul class="list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('bookings.store') }}" method="POST">
                        @csrf

                        <div class="mb-8">
                            <h3 class="text-lg font-bold text-gray-800 mb-3 border-b pb-2">1. Fasilitas Ruangan</h3>
                            <label for="room_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih Ruangan (Opsional)</label>
                            <select name="room_id" id="room_id" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">-- Tanpa Ruangan (Hanya Pinjam Aset) --</option>
                                @foreach($rooms as $r)
                                    <option value="{{ $r->id }}" {{ (old('room_id', $selectedRoom ?? '') == $r->id) ? 'selected' : '' }}>
                                        {{ $r->name }} (Kapasitas maksimal: {{ $r->capacity }} orang)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-8">
                            <h3 class="text-lg font-bold text-gray-800 mb-3 border-b pb-2">2. Detail Kegiatan</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="purpose" class="block text-sm font-medium text-gray-700 mb-1">Tujuan / Nama Kegiatan <span class="text-red-500">*</span></label>
                                    <input type="text" name="purpose" id="purpose" value="{{ old('purpose') }}" required class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Contoh: Latihan Koor Wilayah">
                                </div>

                                <div>
                                    <label for="attendees" class="block text-sm font-medium text-gray-700 mb-1">Estimasi Jumlah Peserta <span class="text-red-500">*</span></label>
                                    <input type="number" name="attendees" id="attendees" value="{{ old('attendees') }}" min="1" required class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Jumlah orang">
                                </div>

                                <div>
                                    <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">Waktu Mulai <span class="text-red-500">*</span></label>
                                    <input type="datetime-local" name="start_time" id="start_time" value="{{ old('start_time') }}" required class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                </div>

                                <div>
                                    <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1">Waktu Selesai <span class="text-red-500">*</span></label>
                                    <input type="datetime-local" name="end_time" id="end_time" value="{{ old('end_time') }}" required class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                </div>
                            </div>
                        </div>

                        <div class="mb-8">
                            <h3 class="text-lg font-bold text-gray-800 mb-2 border-b pb-2">3. Peminjaman Aset Paroki (Opsional)</h3>
                            <p class="text-sm text-gray-500 mb-4">Centang aset yang ingin dipinjam dan pastikan untuk mengisi jumlah yang dibutuhkan.</p>
                            
                            @php
                                // Mengambil data array aset yang sebelumnya dicentang jika terjadi error validasi
                                $oldAssets = old('asset_ids', []);
                            @endphp

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @forelse($assets as $asset)
                                    <div class="border border-gray-200 rounded-lg p-4 flex items-start space-x-3 hover:bg-gray-50 transition">
                                        <div class="flex-shrink-0 mt-1">
                                            <input type="checkbox" name="asset_ids[]" value="{{ $asset->id }}" id="asset_{{ $asset->id }}" 
                                                class="asset-checkbox w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                                {{ in_array($asset->id, $oldAssets) ? 'checked' : '' }}>
                                        </div>
                                        <div class="flex-1">
                                            <label for="asset_{{ $asset->id }}" class="block text-sm font-bold text-gray-900 cursor-pointer">
                                                {{ $asset->asset_name }}
                                            </label>
                                            <p class="text-xs text-gray-500 mb-2">Stok Tersedia: <span class="font-semibold text-green-600">{{ $asset->stock_available }} unit</span></p>
                                            
                                            <div>
                                                <input type="number" name="quantities[]" id="qty_{{ $asset->id }}" 
                                                    min="1" max="{{ $asset->stock_available }}" 
                                                    value="1" 
                                                    disabled 
                                                    class="asset-qty w-full text-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm disabled:bg-gray-100 disabled:text-gray-400" 
                                                    placeholder="Jumlah Pinjam">
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-span-full p-4 bg-gray-50 text-center rounded-lg border border-dashed border-gray-300">
                                        <p class="text-sm text-gray-500 italic">Tidak ada aset yang tersedia untuk dipinjam saat ini.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <div class="mb-8 bg-blue-50 p-5 rounded-lg border border-blue-100">
                            <div class="flex items-start">
                                <div class="flex items-center h-5 mt-0.5">
                                    <input id="sop_agreement" name="sop_agreement" type="checkbox" required class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="sop_agreement" class="font-bold text-gray-900">Saya menyetujui Syarat & Ketentuan Peminjaman Paroki <span class="text-red-500">*</span></label>
                                    <p class="text-gray-600 mt-1">Dengan mencentang kotak ini, saya menyatakan bertanggung jawab penuh atas kebersihan ruangan dan/atau keutuhan aset yang dipinjam. Saya bersedia mengganti rugi apabila terjadi kerusakan atau kehilangan selama masa peminjaman.</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end border-t pt-5">
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-3">
                                Batal
                            </a>
                            <button type="submit" class="inline-flex items-center px-6 py-2 bg-indigo-600 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                                Ajukan Peminjaman
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.asset-checkbox');
            
            // Fungsi untuk mengatur status input quantity
            function toggleQuantityInput(checkbox) {
                const assetId = checkbox.id.split('_')[1];
                const qtyInput = document.getElementById('qty_' + assetId);
                
                if (checkbox.checked) {
                    qtyInput.disabled = false;
                    qtyInput.required = true;
                } else {
                    qtyInput.disabled = true;
                    qtyInput.required = false;
                }
            }

            checkboxes.forEach(function(checkbox) {
                // Jalankan fungsi saat halaman pertama dimuat (berguna jika ada old data/error validasi)
                toggleQuantityInput(checkbox);

                // Jalankan fungsi setiap kali checkbox di-klik
                checkbox.addEventListener('change', function() {
                    toggleQuantityInput(this);
                });
            });
        });
    </script>
</x-app-layout>