<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    /**
     * Menampilkan daftar semua produk.
     */
    public function index()
    {
        // Ambil data produk dengan relasi kategori untuk menghindari N+1 query problem
        $products = Product::with('category')->latest()->paginate(8);

        // Arahkan ke view yang benar di dalam folder admin
        return view('admin.products', compact('products'));
    }

    /**
     * Menampilkan form untuk menambah produk baru.
     */
    public function create()
{
    $categories = Category::all();
    return view('admin.products.create', compact('categories'));
}

public function store(Request $request)
{
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'category_id' => 'required|exists:categories,id',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    if ($request->hasFile('image')) {
        $validatedData['image'] = basename($request->file('image')->store('images/products', 'public'));
    }

    Product::create($validatedData);

    return redirect()->route('dashboard.products')->with('success', 'Produk berhasil ditambahkan!');
}

    /**
     * Menampilkan form untuk mengedit produk.
     */
    public function edit(Product $product) // Menggunakan Route Model Binding
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Memperbarui data produk di database.
     */
    public function update(Request $request, Product $product) // Menggunakan Route Model Binding
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // image tidak wajib diisi saat update
        ]);

        if ($request->hasFile('image')) {

            $validatedData['image'] = basename($request->file('image')->store('images/products', 'public'));
        }

        $product->update($validatedData);

        return redirect()->route('dashboard.products')->with('success', 'Produk berhasil diperbarui!');
    }

    /**
     * Menghapus produk dari database.
     */
    public function destroy(Product $product) // Menggunakan Route Model Binding
    {
        $product->delete();

        return redirect()->route('dashboard.products')->with('success', 'Produk berhasil dihapus!');
    }
}