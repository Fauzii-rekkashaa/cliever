<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LandingController extends Controller
{
    public function index()
    {
        // Kalau user sudah login, otomatis arahkan ke dashboard
        // sesuai role-nya, jangan tampilkan landing page lagi.
        if (Auth::check()) {
            $user = Auth::user();

            return match ($user->role) {
                'admin'    => redirect()->route('admin.dashboard'),
                'pengajar' => redirect()->route('pengajar.dashboard'),
                'pelajar'  => redirect()->route('pelajar.dashboard'),
                default    => view('landing'),
            };
        }

        return view('landing');
    }
}
