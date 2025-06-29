<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    protected function redirectTo(Request $request): ?string
    {
        if (! $request->expectsJson()) {
            // Cek jika URL yang diakses berawalan 'admin/'
            if ($request->is('admin/*')) {
                return route('admin.login');
            }
            
            // Untuk rute selain admin, gunakan rute login default
            return route('login');
        }

        return null;
    }
}