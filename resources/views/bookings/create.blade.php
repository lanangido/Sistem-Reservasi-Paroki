<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Formulir Peminjaman Ruangan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                            role="alert">
                            <strong class="font-bold">Mohon perbaiki isian berikut:</strong>
                            <ul class="mt-2 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="mb-6 bg-blue-50 p-4 rounded-lg border border-blue-100">
                        <h3 class="font-bold text-blue-800 text-lg">Ruangan Terpilih: {{ $room->name }}</h3>
                        <p class="text-blue-600 text-sm">Kapasitas Maksimal: {{ $room->capacity }} Orang</p>
                    </div>

                    <form method="POST" action="{{ route('booking.store', $room->id) }}">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="purpose">Nama / Tujuan
                                Kegiatan</label>
                            <input type="text" name="purpose" id="purpose"
                                class="shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md w-full"
                                required placeholder="Contoh: Rapat Panitia Natal">
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="attendees">Estimasi Jumlah
                                Peserta</label>
                            <input type="number" name="attendees" id="attendees"
                                class="shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md w-full"
                                required max="{{ $room->capacity }}" placeholder="Maksimal {{ $room->capacity }} orang">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="start_time">Waktu
                                    Mulai</label>
                                <input type="datetime-local" name="start_time" id="start_time"
                                    class="shadow-sm border-gray-300 rounded-md w-full" required>
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="end_time">Waktu
                                    Selesai</label>
                                <input type="datetime-local" name="end_time" id="end_time"
                                    class="shadow-sm border-gray-300 rounded-md w-full" required>
                                <p class="text-xs text-gray-500 mt-1">Maksimal durasi 3 jam per slot.</p>
                            </div>
                        </div>

                        <div class="mb-6 p-4 border border-orange-200 bg-orange-50 rounded-lg">
                            <label class="flex items-start">
                                <input type="checkbox" name="sop_agreement"
                                    class="mt-1 rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    required>
                                <span class="ml-2 text-sm text-gray-700">
                                    <strong>Kontrak Kebersihan:</strong> Saya bersedia membawa pulang sampah operasional
                                    dan tidak menggunakan kemasan styrofoam selama kegiatan berlangsung.
                                </span>
                            </label>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('dashboard') }}"
                                class="text-gray-500 hover:text-gray-700 mr-4 font-semibold text-sm">Batal</a>
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg">
                                Ajukan Jadwal
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>