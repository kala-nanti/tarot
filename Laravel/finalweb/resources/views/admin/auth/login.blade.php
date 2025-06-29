@extends('admin.layouts.guest')

@section('title', 'Login Admin')
@section('page_title', 'Login Admin Restoran')

@section('content')
<div class="max-w-md mx-auto mt-10 bg-white shadow-lg rounded-lg p-8 border-2 border-pink-400">
    <h2 class="text-3xl font-extrabold text-center text-red-600 mb-6">Login Admin</h2>
    
    <form method="POST" action="{{ route('admin.login') }}">
        @csrf

        <div class="mb-4">
            <label for="email" class="block text-gray-800 text-sm font-semibold mb-2">Email</label>
            <input type="email" id="email" name="email"
                class="border border-pink-300 rounded-lg w-full px-4 py-2 text-gray-900 focus:ring-2 focus:ring-pink-400 focus:outline-none @error('email') border-red-500 @enderror"
                value="{{ old('email') }}" required autofocus>
            @error('email')
                <p class="text-red-500 text-sm italic mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password" class="block text-gray-800 text-sm font-semibold mb-2">Password</label>
            <input type="password" id="password" name="password"
                class="border border-pink-300 rounded-lg w-full px-4 py-2 text-gray-900 focus:ring-2 focus:ring-pink-400 focus:outline-none @error('password') border-red-500 @enderror"
                required>
            @error('password')
                <p class="text-red-500 text-sm italic mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between mb-4">
            <label for="remember_me" class="inline-flex items-center text-gray-700 text-sm">
                <input id="remember_me" type="checkbox"
                    class="rounded border-gray-300 text-pink-600 shadow-sm focus:ring-pink-400" name="remember">
                <span class="ml-2">Ingat Saya</span>
            </label>
            <a href="{{ route('admin.password.request') }}"
                class="text-sm text-pink-500 hover:underline font-medium">
                Lupa Password?
            </a>
        </div>

        <div class="mb-4">
    <button type="submit"
        class="w-full relative text-white font-bold py-2 px-4 rounded-lg
               bg-red-600 hover:bg-red-700
               shadow-md transition duration-300 overflow-hidden">

        <span class="relative z-10">Masuk Sekarang</span>

        <!-- Glossy effect at the top -->
        <div class="absolute top-0 left-0 w-full h-1/2 bg-white opacity-10 rounded-t-lg pointer-events-none"></div>
    </button>
</div>

        <p class="text-center text-sm text-gray-700">
            Belum punya akun?
            <a href="{{ route('admin.register') }}" class="text-pink-500 font-semibold hover:underline">Registrasi di sini</a>
        </p>
    </form>
</div>
@endsection
