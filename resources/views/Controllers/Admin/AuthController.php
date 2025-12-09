<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string',
            'password' => 'required',
        ]);

        // Cek di tabel admin (hanya dengan email)
        if (filter_var($credentials['email'], FILTER_VALIDATE_EMAIL)) {
            if (Auth::guard('admin')->attempt($credentials)) {
                $request->session()->regenerate();
                
                return redirect()->intended('/admin');
            }
        }
        
        // Cek di tabel guru (dengan email atau NIK)
        $guru = \App\Models\Guru::where(function($query) use ($credentials) {
            $query->where('email', $credentials['email']);
            // Cek NIK jika input adalah angka dan panjangnya 16 digit
            if (is_numeric($credentials['email']) && strlen($credentials['email']) == 16) {
                $query->orWhere('nik', $credentials['email']);
            }
        })->first();
        
        if ($guru && \Illuminate\Support\Facades\Hash::check($credentials['password'], $guru->password)) {
            // Login menggunakan guard admin tapi dengan data guru
            Auth::guard('admin')->login($guru);
            $request->session()->regenerate();
            
            return redirect()->intended('/admin');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/admin/login');
    }
}
