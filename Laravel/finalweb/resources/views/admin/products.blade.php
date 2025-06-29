@extends('admin.layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-7">
        <h1 class="text-3xl font-bold">Products</h1>
        <a href="{{ route('dashboard.products.create') }}" class="bg-red-500 text-white font-bold px-6 py-3 rounded-lg shadow-md hover:bg-red-600 transition-colors">
            <i class="ri-add-line mr-2"></i>
            Add Product
        </a>
    </div>
    
    {{-- Notifikasi Sukses --}}
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-md" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-xl shadow-md overflow-x-auto">
        <table class="w-full text-left">
            <thead class="text-gray-600">
                <tr class="border-b border-gray-200">
                    <th class="w-2/12 text-center py-4 px-4">Image</th>
                    <th class="w-3/12 py-4 px-4">Product Name</th>
                    <th class="w-2/12 py-4 px-4">Category</th>
                    <th class="w-2/12 py-4 px-4">Price</th>
                    <th class="w-1/12 py-4 px-4">Stock</th>
                    <th class="w-2/12 py-4 px-4 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">

            @forelse ( $products as $product )
                <tr class="hover:bg-gray-50 font-medium text-gray-800">
                    <td class="py-4 px-4 flex justify-center">
                        <div class="w-14 h-14 overflow-hidden rounded-lg">
                            <img src="{{ asset('storage/images/products/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover"/>
                        </div>
                    </td>
                    <td class="py-4 px-4">{{ $product->name }}</td>
                    <td class="py-4 px-4">{{ $product->category->name ?? 'Uncategorized' }}</td>
                    <td class="py-4 px-4">Rp. {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td class="py-4 px-4">{{ $product->stock }}</td>
                    <td class="py-4 px-4 text-center">
                        <div class="inline-flex rounded-lg shadow-sm">
                            <a href="{{ route('dashboard.products.edit', $product->id) }}" class="px-4 py-2 text-sm font-medium text-blue-600 bg-white border border-gray-200 rounded-l-lg hover:bg-gray-100 focus:z-10 focus:ring-2 focus:ring-blue-500" aria-label="edit">
                                <i class="ri-edit-box-line"></i>
                            </a>
                            <form action="{{ route('dashboard.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 text-sm font-medium text-red-600 bg-white border-t border-b border-r border-gray-200 rounded-r-lg hover:bg-gray-100 focus:z-10 focus:ring-2 focus:ring-red-500" aria-label="delete">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-10 text-gray-500">
                        Belum ada produk. Silakan tambahkan produk baru.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-7">
        {{ $products->links() }}
    </div>
@endsection