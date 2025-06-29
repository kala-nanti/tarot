@extends('admin.layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-7">
        <h1 class="text-3xl font-bold text-gray-800">Daftar Transaksi</h1>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-md" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-xl shadow-md overflow-x-auto">
        <table class="w-full text-left">
            <thead class="text-gray-600">
                <tr class="border-b border-gray-200">
                    <th class="py-4 px-4">ID Transaksi</th>
                    <th class="py-4 px-4">Nama Pelanggan</th>
                    <th class="py-4 px-4">Total Harga</th>
                    <th class="py-4 px-4">Tanggal</th>
                    <th class="py-4 px-4 text-center">Status</th>
                    <th class="py-4 px-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($orders as $order)
                    <tr class="hover:bg-gray-50 font-medium text-gray-800">
                        <td class="py-4 px-4 font-bold">#{{ $order->id }}</td>
                        <td class="py-4 px-4">{{ $order->user->name ?? 'Pelanggan' }}</td>
                        <td class="py-4 px-4">Rp. {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        <td class="py-4 px-4">{{ $order->created_at->format('d M Y, H:i') }}</td>
                        <td class="py-4 px-4 text-center">
                            @if($order->status == 'selesai')
                                <span class="px-3 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Selesai</span>
                            @elseif($order->status == 'diproses')
                                <span class="px-3 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">Diproses</span>
                            @else
                                <span class="px-3 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Dibatalkan</span>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-center">
                            <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="border border-gray-300 rounded-md text-sm" onchange="this.form.submit()">
                                    <option value="diproses" @if($order->status == 'diproses') selected @endif>Di Proses</option>
                                    <option value="selesai" @if($order->status == 'selesai') selected @endif>Selesai</option>
                                    <option value="dibatalkan" @if($order->status == 'dibatalkan') selected @endif>Dibatalkan</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-10 text-gray-500">
                            Tidak ada data transaksi yang ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-7">
        {{ $orders->links() }}
    </div>
@endsection
