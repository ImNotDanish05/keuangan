<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required','string'],
            'password' => ['required','string'],
        ]);

        $user = User::where('username', $credentials['username'])->first();
        if (!$user || !$user->is_approved || !in_array($user->role, ['owner','admin'])) {
            return back()->withErrors(['username' => 'Kredensial tidak valid atau akun belum disetujui.'])->withInput();
        }

        if (!Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']], $request->boolean('remember'))) {
            return back()->withErrors(['username' => 'Kredensial tidak valid.'])->withInput();
        }

        $request->session()->regenerate();
        return redirect()->intended(route('dashboard'));
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'username' => ['required','string','max:255','unique:users,username'],
            'email' => ['nullable','email','max:255','unique:users,email'],
            'password' => [Password::defaults()],
        ]);

        User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'] ?? ($data['username'].'@local.test'),
            'password' => Hash::make($data['password']),
            'role' => 'user',
            'is_approved' => false,
        ]);

        return redirect()->route('login')->with('status', 'Akun dibuat. Menunggu persetujuan admin.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
