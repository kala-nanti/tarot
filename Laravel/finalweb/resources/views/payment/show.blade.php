@extends('admin.layouts.store')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100 p-4">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8 text-center">
        
        <h1 class="text-2xl font-bold mb-4">Selesaikan Pembayaran</h1>
        <p class="text-gray-600 mb-6">Silakan pindai QR Code di bawah ini untuk membayar pesanan Anda.</p>

        {{-- Notifikasi Error --}}
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- Placeholder untuk QR Code --}}
        <div class="mb-6">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=Total:Rp{{$transaction->total_price}}" alt="QR Code Pembayaran" class="mx-auto">
        </div>

        <div class="text-left mb-6 bg-gray-50 p-4 rounded-lg">
            <div class="flex justify-between">
                <span class="font-semibold">ID Transaksi:</span>
                <span>#{{ $transaction->id }}</span>
            </div>
            <div class="flex justify-between font-bold text-lg mt-2">
                <span>Total Pembayaran:</span>
                <span>Rp. {{ number_format($transaction->total_price, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Form untuk tombol konfirmasi pembayaran --}}
        <form action="{{ route('payment.confirm') }}" method="POST">
            @csrf
            <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-lg font-semibold text-lg hover:bg-green-700">
                Saya Sudah Bayar
            </button>
        </form>

        <a href="{{ route('menu') }}" class="mt-4 inline-block text-sm text-gray-500 hover:underline">Batalkan dan Kembali ke Menu</a>

    </div>
</div>
@endsection
