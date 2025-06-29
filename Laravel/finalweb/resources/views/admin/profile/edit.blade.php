@extends('admin.layouts.app')

@section('content')
    <h1 class="text-3xl font-bold mb-7">Profil Admin</h1>

    {{-- Notifikasi Sukses --}}
    @if (session('status'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <div class="bg-white p-8 rounded-xl shadow-md">
        <form method="POST" action="{{ route('admin.profile.update') }}">
            @csrf
            @method('PATCH') {{-- Method untuk update --}}

            {{-- Name --}}
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                <input id="name" type="text" name="name" value="{{ old('name', $admin->name) }}" required autofocus autocomplete="name"
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email Address --}}
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Alamat Email</label>
                <input id="email" type="email" name="email" value="{{ old('email', $admin->email) }}" required autocomplete="username"
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru (Biarkan kosong jika tidak ingin ganti)</label>
                <input id="password" type="password" name="password" autocomplete="new-password"
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500">
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div class="mb-6">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password"
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500">
            </div>

            <div class="flex items-center justify-end">
                <button type="submit" class="px-6 py-2 bg-red-500 text-white font-semibold rounded-lg shadow-md hover:bg-red-600">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
@endsection