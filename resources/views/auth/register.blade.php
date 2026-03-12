<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar Akun - Reservasi Paroki Paulus Miki</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-gray-900 bg-white">

    <div class="min-h-screen flex w-full">

        <div class="hidden lg:flex lg:w-1/2 relative bg-indigo-900 items-center justify-center overflow-hidden">
            <img src="{{ asset('images/bg-gereja.jpg') }}" alt="Gereja Latar Belakang"
                class="absolute inset-0 w-full h-full object-cover opacity-80 mix-blend-overlay">
            <div class="absolute inset-0 bg-gradient-to-t from-indigo-900 via-indigo-900/60 to-transparent"></div>

            <div class="relative z-10 text-center px-12">
                <div
                    class="inline-block p-4 bg-white/10 backdrop-blur-sm rounded-full mb-6 border border-white/20 shadow-lg">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                        </path>
                    </svg>
                </div>
                <h1 class="text-4xl font-extrabold text-white mb-4 tracking-wide leading-tight">
                    Pendaftaran<br>Perwakilan Umat</h1>
                <p class="text-lg text-indigo-200 font-medium max-w-md mx-auto">Sistem Informasi Manajemen Aset dan
                    Reservasi Fasilitas Terpadu</p>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 lg:p-16 relative bg-white">

            <div class="absolute inset-0 lg:hidden">
                <img src="{{ asset('images/bg-gereja.jpg') }}" alt="Background HP"
                    class="w-full h-full object-cover opacity-80">
                <div class="absolute inset-0 bg-gray-50/90 backdrop-blur-sm"></div>
            </div>

            <div
                class="w-full max-w-md relative z-10 bg-white lg:bg-transparent p-8 lg:p-0 rounded-3xl shadow-2xl lg:shadow-none border border-gray-100 lg:border-none">

                <div class="text-center lg:text-left mb-8">
                    <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-2">Buat Akun Baru</h2>
                    <p class="text-sm text-gray-500 font-medium">Lengkapi data perwakilan lingkungan atau komunitas
                        Anda.</p>
                </div>

                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg text-sm">
                        <strong class="font-bold">Gagal Mendaftar:</strong>
                        <ul class="mt-2 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-bold text-gray-700 mb-1">Nama Perwakilan</label>
                        <input id="name" name="name" type="text" required autofocus
                            class="appearance-none rounded-xl relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-gray-50 focus:bg-white transition-colors shadow-sm"
                            placeholder="Nama Lengkap Anda" value="{{ old('name') }}">
                    </div>

                    <div>
                        <label for="lingkungan" class="block text-sm font-bold text-gray-700 mb-1">Asal Lingkungan /
                            Komunitas</label>
                        <input id="lingkungan" name="lingkungan" type="text" required
                            class="appearance-none rounded-xl relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-gray-50 focus:bg-white transition-colors shadow-sm"
                            placeholder="Contoh: Lingkungan St. Yohanes / OMK" value="{{ old('lingkungan') }}">
                    </div>

                    <div>
                        <label for="phone_number" class="block text-sm font-bold text-gray-700 mb-1">Nomor
                            WhatsApp</label>
                        <input id="phone_number" name="phone_number" type="text" required
                            class="appearance-none rounded-xl relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-gray-50 focus:bg-white transition-colors shadow-sm"
                            placeholder="Contoh: 081234567890" value="{{ old('phone_number') }}">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-bold text-gray-700 mb-1">Alamat Email</label>
                        <input id="email" name="email" type="email" required
                            class="appearance-none rounded-xl relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-gray-50 focus:bg-white transition-colors shadow-sm"
                            placeholder="email@contoh.com" value="{{ old('email') }}">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-sm font-bold text-gray-700 mb-1">Kata Sandi</label>
                            <input id="password" name="password" type="password" required
                                class="appearance-none rounded-xl relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-gray-50 focus:bg-white transition-colors shadow-sm"
                                placeholder="Minimal 8 karakter">
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-bold text-gray-700 mb-1">Ulangi
                                Sandi</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" required
                                class="appearance-none rounded-xl relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-gray-50 focus:bg-white transition-colors shadow-sm"
                                placeholder="Ulangi sandi">
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit"
                            class="w-full flex justify-center py-4 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-300 shadow-md hover:shadow-lg">
                            Daftarkan Akun
                        </button>
                    </div>

                    <div class="text-center mt-6 pt-4 border-t border-gray-100">
                        <p class="text-sm text-gray-600">
                            Sudah punya akun?
                            <a href="{{ route('login') }}"
                                class="font-bold text-indigo-600 hover:text-indigo-800 transition-colors">Masuk di
                                sini</a>
                        </p>
                    </div>
                </form>

            </div>
        </div>
    </div>

</body>

</html>