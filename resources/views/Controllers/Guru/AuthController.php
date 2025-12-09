<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('guru.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string',
            'password' => 'required',
        ]);

        // Cek di tabel guru (dengan email atau NIK)
        $guru = Guru::where(function($query) use ($credentials) {
            $query->where('email', $credentials['email']);
            // Cek NIK jika input adalah angka dan panjangnya 16 digit
            if (is_numeric($credentials['email']) && strlen($credentials['email']) == 16) {
                $query->orWhere('nik', $credentials['email']);
            }
        })->first();
        
        if ($guru && Hash::check($credentials['password'], $guru->password)) {
            // Login menggunakan guard guru
            Auth::guard('guru')->login($guru);
            $request->session()->regenerate();
            
            return redirect()->intended('/guru');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('guru')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/guru/login');
    }
}