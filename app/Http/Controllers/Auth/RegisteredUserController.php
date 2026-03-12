<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'lingkungan' => ['nullable', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:20'],
        ]);

        // ==========================================
        // MULAI SIHIR FORMAT NOMOR WHATSAPP (FONNTE)
        // ==========================================
        $nomor_hp = $request->phone_number;

        // 1. Bersihkan karakter aneh: Hapus spasi, strip, kurung, atau tanda plus (+)
        $nomor_hp = preg_replace('/[^0-9]/', '', $nomor_hp);

        // 2. Jika nomor dimulai dengan angka '0', ubah menjadi '62'
        if (str_starts_with($nomor_hp, '0')) {
            $nomor_hp = '62' . substr($nomor_hp, 1);
        }
        // ==========================================
        // SELESAI
        // ==========================================

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'umat',
            'lingkungan' => $request->lingkungan,
            'phone_number' => $nomor_hp, // <-- Masukkan nomor yang sudah disihir ke database
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}