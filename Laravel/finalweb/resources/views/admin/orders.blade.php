@extends('admin.layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-7">
        <h1 class="text-3xl font-bold">Daftar Transaksi</h1>
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

                @forelse($transactions as $transaction)
                <tr class="hover:bg-gray-50 font-medium text-gray-800">
                    <td class="py-4 px-4 font-bold">#{{ $transaction->id }}</td>
                    <td class="py-4 px-4">{{ $transaction->user->name ?? 'Guest' }}</td>
                    <td class="py-4 px-4">Rp. {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                    <td class="py-4 px-4">{{ $transaction->created_at->format('d M Y, H:i') }}</td>
                    <td class="py-4 px-4 text-center">
                        @if($transaction->status == 'completed')
                            <span class="px-3 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Completed</span>
                        @elseif($transaction->status == 'processing')
                            <span class="px-3 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">Processing</span>
                        @else
                            <span class="px-3 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Cancelled</span>
                        @endif
                    </td>
                    <td class="py-4 px-4 text-center">
                        @if($transaction->status == 'processing')
                        <form action="{{ route('dashboard.orders.complete', $transaction->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="text-sm font-semibold text-white bg-blue-500 hover:bg-blue-600 px-3 py-1 rounded-full">
                                Tandai Selesai
                            </button>
                        </form>
                        @else
                        -
                        @endif
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
        {{ $transactions->links() }}
    </div>
@endsection