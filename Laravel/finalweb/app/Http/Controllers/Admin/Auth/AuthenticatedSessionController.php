<?php

// Lokasi file: app/Http/Controllers/Admin/Auth/AuthenticatedSessionController.php
namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AdminLoginRequest; // Kita akan buat file ini setelahnya
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Menampilkan halaman/view untuk login.
     */
    public function create(): View
    {
        return view('admin.auth.login'); // Mengarahkan ke view login admin
    }

    /**
     * Menangani permintaan login yang masuk.
     */
    public function store(AdminLoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Mengarahkan ke halaman dashboard admin setelah login berhasil
        return redirect()->intended(RouteServiceProvider::ADMIN_HOME);
    }

    /**
     * Menghancurkan sesi autentikasi (logout).
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // Mengarahkan kembali ke halaman login admin setelah logout
        return redirect()->route('admin.login');
    }
}