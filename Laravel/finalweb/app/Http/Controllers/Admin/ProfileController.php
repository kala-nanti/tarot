<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Menampilkan form untuk mengedit profil.
     */
    public function edit()
    {
        // Mengambil data admin yang sedang login
        $admin = Auth::guard('admin')->user();
        
        // Menampilkan view dan mengirimkan data admin
        return view('admin.profile.edit', compact('admin'));
    }

    /**
     * Memperbarui informasi profil admin.
     */
    public function update(Request $request)
    {
        /** @var \App\Models\Admin $admin */
        $admin = Auth::guard('admin')->user();

        // Validasi input
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins,email,' . $admin->id],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        // Siapkan data untuk diupdate, kecuali password
        $updateData = [
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
        ];

        // Jika ada password baru yang diisi, hash dan tambahkan ke data update
        if (!empty($validatedData['password'])) {
            $updateData['password'] = Hash::make($validatedData['password']);
        }

        // Gunakan method update() untuk mass-assignment
        $admin->update($updateData);

        // Redirect kembali dengan pesan sukses
        return redirect()->route('admin.profile.edit')->with('status', 'Profil berhasil diperbarui!');
    }
}