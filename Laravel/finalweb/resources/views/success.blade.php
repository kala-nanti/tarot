@extends('admin.layouts.store')

@section('content')
<div class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-lg bg-white rounded-2xl shadow-lg p-8 text-center">
        <div class="text-green-500 mb-4">
            <i class="ri-checkbox-circle-fill text-8xl"></i>
        </div>
        <h1 class="text-3xl font-bold mb-2">Pesanan Diterima!</h1>
        <p class="text-gray-600 mb-6">Terima kasih! Pesanan Anda dengan ID <span class="font-bold">#{{ $transaction->id }}</span> sedang kami proses.</p>
        <a href="{{ route('menu') }}" class="inline-block px-8 py-3 bg-red-500 text-white font-bold rounded-lg shadow-md hover:bg-red-600 transition-colors">
            Pesan Lagi
        </a>
    </div>
</div>
@endsection