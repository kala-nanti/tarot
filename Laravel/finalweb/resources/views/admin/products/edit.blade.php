@extends('admin.layouts.app')

@section('content')
<h1 class="text-3xl font-bold mb-7">Edit Product</h1>

<div class="bg-white p-8 rounded-xl shadow-md">
    <form action="{{ route('dashboard.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT') {{-- Method ini penting untuk update --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Product Name --}}
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
                <input type="text" name="name" id="name" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500" value="{{ old('name', $product->name) }}" required>
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Category --}}
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                <select name="category_id" id="category_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Price --}}
            <div>
                <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price (Rp)</label>
                <input type="number" name="price" id="price" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500" value="{{ old('price', $product->price) }}" required>
                @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Stock --}}
            <div>
                <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
                <input type="number" name="stock" id="stock" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500" value="{{ old('stock', $product->stock) }}" required>
                @error('stock') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            
            {{-- Image --}}
            <div class="md:col-span-2">
                <label for="image" class="block text-sm font-medium text-gray-700 mb-1">New Product Image (Biarkan kosong jika tidak ingin ganti)</label>
                 <div class="flex items-center gap-4 mt-2">
                    <img src="{{ asset('storage/images/products/' . $product->image) }}" alt="{{ $product->name }}" class="w-20 h-20 rounded-lg object-cover border p-1">
                    <input type="file" name="image" id="image" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                </div>
                 @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mt-8 flex justify-end gap-4">
            <a href="{{ route('dashboard.products') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100">Cancel</a>
            <button type="submit" class="px-6 py-2 bg-red-500 text-white font-semibold rounded-lg shadow-md hover:bg-red-600">Update Product</button>
        </div>
    </form>
</div>
@endsection