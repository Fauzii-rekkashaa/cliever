<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    // ─── Show Register ───────────────────────────────────────────
    public function showRegister(Request $request)
    {
        $role = $request->query('role', 'pelajar');
        return view('auth.register', compact('role'));
    }

    // ─── Handle Register ─────────────────────────────────────────
    public function register(Request $request)
    {
        $role = $request->input('role', 'pelajar');

        $rules = [
            'nama'     => ['nullable', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:50', 'unique:users,username'],
            'email'    => ['required', 'email', 'max:100', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'role'     => ['required', 'in:pelajar,pengajar'],
        ];

        if ($role === 'pengajar') {
            $rules['sertifikat'] = ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'];
        }

        $validated = $request->validate($rules);

        $sertifikatPath = null;
        if ($role === 'pengajar' && $request->hasFile('sertifikat')) {
            $sertifikatPath = $request->file('sertifikat')->store('sertifikat', 'public');
        }

        User::create([
            'nama'       => $validated['nama'] ?? null,
            'username'   => $validated['username'],
            'email'      => $validated['email'],
            'password'   => Hash::make($validated['password']),
            'role'       => $validated['role'],
            'status'     => $role === 'pengajar' ? 'menunggu' : 'aktif',
            'sertifikat' => $sertifikatPath,
        ]);

        $pesan = $role === 'pengajar'
            ? 'Pendaftaran berhasil! Akun kamu sedang menunggu konfirmasi dari admin.'
            : 'Akun berhasil dibuat! Silakan login.';

        return redirect()->route('login')->with('success', $pesan);
    }

    // ─── Show Login ──────────────────────────────────────────────
    public function showLogin()
    {
        return view('auth.login');
    }

    // ─── Handle Login ────────────────────────────────────────────
    public function login(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role === 'pengajar' && $user->status === 'menunggu') {
                Auth::logout();
                $request->session()->invalidate();
                return back()->withErrors([
                    'username' => 'Akun kamu sedang menunggu konfirmasi dari admin.'
                ])->onlyInput('username');
            }

            if ($user->role === 'pengajar' && $user->status === 'ditolak') {
                Auth::logout();
                $request->session()->invalidate();
                return back()->withErrors([
                    'username' => 'Akun kamu ditolak oleh admin. Hubungi admin untuk informasi lebih lanjut.'
                ])->onlyInput('username');
            }

            return $this->redirectByRole($user);
        }

        return back()
            ->withErrors(['username' => 'Username atau password salah.'])
            ->onlyInput('username');
    }

    // ─── Logout ──────────────────────────────────────────────────
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    // ─── Helper redirect by role ─────────────────────────────────
    private function redirectByRole($user)
    {
        return match ($user->role) {
            'admin'    => redirect()->route('admin.dashboard'),
            'pengajar' => redirect()->route('pengajar.dashboard'),
            'pelajar'  => redirect()->route('pelajar.dashboard'),
            default    => redirect()->route('home'),
        };
    }
}
