@extends('admin.layouts.app')

@section('title', 'Lupa Password')
@section('page_title', 'Lupa Password Admin')

@section('content')
    @if (session('status'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.password.email') }}">
        @csrf

        <div class="mb-4">
            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
            <input type="email" id="email" name="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror" value="{{ old('email') }}" required autofocus>
            @error('email')
                <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">
                Kirim Tautan Reset Password
            </button>
        </div>

        <p class="text-center text-gray-600 text-sm mt-4">
            <a href="{{ route('admin.login') }}" class="text-blue-500 hover:text-blue-800 font-bold">Kembali ke Login</a>
        </p>
    </form>
@endsection