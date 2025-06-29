<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password; // Digunakan untuk broker password

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('admin.auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Menggunakan broker password 'admins'
        $response = Password::broker('admins')->sendResetLink(
            $request->only('email')
        );

        return $response == Password::RESET_LINK_SENT
                    ? back()->with('status', __($response))
                    : back()->withErrors(['email' => __($response)]);
    }
}