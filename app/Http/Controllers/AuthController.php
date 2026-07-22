<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Register publik hanya untuk staff sebagai pengaju cuti.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:users,email',
            'tanggal_lahir'  => 'required|date',
            'jenis_kelamin'  => 'required|in:L,P',
            'password'       => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name'           => $request->name,
            'email'          => $request->email,
            'tanggal_lahir'  => $request->tanggal_lahir,
            'jenis_kelamin'  => $request->jenis_kelamin,
            'password'       => Hash::make($request->password),
            'role'           => 'staff',
        ]);

        Auth::login($user);
        return redirect()->intended(route('pegawai.dashboard'));
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            if (in_array($user->role, ['head_department', 'gm'])) {
                return redirect()->intended(route('admin.dashboard'));
            }

            if ($user->role === 'hrd') {
                return redirect()->intended(route('admin.dashboard'));
            }

            if ($user->role === 'staff') {
                return redirect()->intended(route('pegawai.dashboard'));
            }

            Auth::logout();
            return redirect()->route('login')->withErrors([
                'email' => 'Role akun belum sesuai. Hubungi administrator.',
            ]);
        }

        return back()->withInput($request->only('email'))
            ->withErrors(['email' => 'Email atau password salah.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Anda telah logout.');
    }
}
