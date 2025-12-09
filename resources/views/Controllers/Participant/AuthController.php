<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Show the participant login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('participant.auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nisn' => 'required_without_all:email|string',
            'email' => 'required_without_all:nisn|string|email',
            'password' => 'required|string',
        ], [
            'nisn.required_without_all' => 'Student ID or Email is required',
            'password.required' => 'Password is required',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        // Determine login field (NISN or Email)
        $loginField = filter_var($request->nisn, FILTER_VALIDATE_EMAIL) ? 'email' : 'nisn';
        $credentials = $request->only($loginField, 'password');

        if (Auth::guard('siswa')->attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended(route('participant.dashboard'));
        }

        return back()
            ->with('error', 'Invalid Student ID/Email or password')
            ->withInput();
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::guard('siswa')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('participant.login');
    }
}