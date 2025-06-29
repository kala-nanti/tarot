@extends('admin.layouts.store')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">Keranjang Belanja Anda</h1>

    {{-- PERBAIKAN: Menambahkan kembali @if yang hilang --}}
    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            <p class="font-bold">Terjadi Kesalahan</p>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            <p>{{ session('error') }}</p>
        </div>
    @endif


    @if(session()->has('cart') && count(session('cart')) > 0)
        <div class="bg-white rounded-xl shadow-md p-6">
            <table class="w-full">
                <thead>
                    <tr class="border-b">
                        <th class="text-left p-3">Produk</th>
                        <th class="text-center p-3">Jumlah</th>
                        <th class="text-right p-3">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalKeseluruhan = 0; @endphp
                    @foreach(session('cart') as $id => $details)
                        @php $totalKeseluruhan += $details['price'] * $details['quantity']; @endphp
                        <tr class="border-b">
                            <td class="p-3">{{ $details['name'] }}</td>
                            <td class="text-center p-3">{{ $details['quantity'] }}</td>
                            <td class="text-right p-3">Rp. {{ number_format($details['price'] * $details['quantity']) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="font-bold text-lg">
                        <td colspan="2" class="text-right p-3">Total Keseluruhan:</td>
                        <td class="text-right p-3">Rp. {{ number_format($totalKeseluruhan) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="mt-8">
            <form action="{{ route('checkout.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="nama_pelanggan" class="block text-sm font-medium text-gray-700">Nama Pelanggan</label>
                    <input type="text" name="nama_pelanggan" id="nama_pelanggan" value="{{ old('nama_pelanggan', auth()->user()->name ?? '') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div class="mt-6">
                    <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold text-lg hover:bg-blue-700">
                        Konfirmasi Pesanan
                    </button>
                </div>
            </form>
        </div>
        
    @else
        <div class="text-center bg-white rounded-xl shadow-md p-10">
            <p class="text-gray-500">Keranjang Anda kosong.</p>
            <a href="{{ route('menu') }}" class="mt-4 inline-block bg-blue-500 text-white px-6 py-2 rounded-lg">Mulai Belanja</a>
        </div>
    @endif
</div>
@endsection
