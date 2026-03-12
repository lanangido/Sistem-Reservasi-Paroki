<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login - Reservasi Paroki Paulus Miki</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900 bg-white">

    <div class="min-h-screen flex w-full">
        
        <div class="hidden lg:flex lg:w-1/2 relative bg-indigo-900 items-center justify-center overflow-hidden">
            <img src="{{ asset('images/bg-gereja.jpg') }}" alt="Gereja Latar Belakang" class="absolute inset-0 w-full h-full object-cover opacity-80 mix-blend-overlay">
            <div class="absolute inset-0 bg-gradient-to-t from-indigo-900 via-indigo-900/60 to-transparent"></div>
            
            <div class="relative z-10 text-center px-12">
                <div class="inline-block p-4 bg-white/10 backdrop-blur-sm rounded-full mb-6 border border-white/20 shadow-lg">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <h1 class="text-4xl font-extrabold text-white mb-4 tracking-wide leading-tight">Paroki Santo Paulus Miki<br>Salatiga</h1>
                <p class="text-lg text-indigo-200 font-medium max-w-md mx-auto">Sistem Informasi Manajemen Aset dan Reservasi Fasilitas Terpadu</p>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 lg:p-24 relative bg-white">
            
            <div class="absolute inset-0 lg:hidden">
                <img src="{{ asset('images/bg-gereja.jpg') }}" alt="Gereja Latar Belakang" class="absolute inset-0 w-full h-full object-cover opacity-80 mix-blend-overlay">
                <div class="absolute inset-0 bg-gray-50/90 backdrop-blur-sm"></div>
            </div>

            <div class="w-full max-w-md relative z-10 bg-white lg:bg-transparent p-8 lg:p-0 rounded-3xl shadow-2xl lg:shadow-none border border-gray-100 lg:border-none">
                
                <div class="lg:hidden text-center mb-8">
                    <div class="mx-auto h-16 w-16 bg-indigo-100 rounded-full flex items-center justify-center mb-4 shadow-inner">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                </div>

                <div class="text-center lg:text-left mb-10">
                    <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-2">Selamat Datang</h2>
                    <p class="text-sm text-gray-500 font-medium">Silakan masuk menggunakan akun Anda untuk melanjutkan.</p>
                </div>

                @if(session('status'))
                    <div class="mb-6 text-center lg:text-left text-red-600 font-bold bg-red-50 p-3 rounded-lg text-sm">
                        {{ session('status') }}
                    </div>
                @endif
                
                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg text-sm">
                        <strong class="font-bold">Login Gagal:</strong>
                        <ul class="mt-2 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div class="space-y-5">
                        <div>
                            <label for="email" class="block text-sm font-bold text-gray-700 mb-2">Alamat Email</label>
                            <input id="email" name="email" type="email" required autofocus class="appearance-none rounded-xl relative block w-full px-4 py-3.5 border border-gray-300 placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-gray-50 focus:bg-white transition-colors shadow-sm" placeholder="umat@paroki.com" value="{{ old('email') }}">
                        </div>

                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <label for="password" class="block text-sm font-bold text-gray-700">Kata Sandi</label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition-colors">
                                        Lupa Sandi?
                                    </a>
                                @endif
                            </div>
                            <input id="password" name="password" type="password" required class="appearance-none rounded-xl relative block w-full px-4 py-3.5 border border-gray-300 placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-gray-50 focus:bg-white transition-colors shadow-sm" placeholder="••••••••">
                        </div>
                    </div>

                    <div class="flex items-center pt-2">
                        <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded cursor-pointer">
                        <label for="remember_me" class="ml-2 block text-sm text-gray-700 cursor-pointer select-none">
                            Ingat sesi saya
                        </label>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full flex justify-center py-4 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-300 shadow-md hover:shadow-lg">
                            Masuk ke Sistem &rarr;
                        </button>
                    </div>
                    
                    <div class="text-center mt-8 pt-6 border-t border-gray-100">
                        <p class="text-sm text-gray-600">
                            Belum terdaftar sebagai umat? 
                            <a href="{{ route('register') }}" class="font-bold text-indigo-600 hover:text-indigo-800 transition-colors">Buat Akun</a>
                        </p>
                    </div>
                </form>
                
                <div class="mt-8 text-center lg:text-left">
                    <p class="text-xs text-gray-400 font-medium">
                        &copy; {{ date('Y') }} Tugas Akhir - Paroki Paulus Miki.
                    </p>
                </div>

            </div>
        </div>
    </div>

</body>
</html>