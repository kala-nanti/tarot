<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class MenuController extends Controller
{
    /**
     * Menampilkan halaman menu dengan semua produk dan kategori.
     */
    public function index()
    {
        // Mengambil semua produk yang tersedia
        $products = Product::latest()->get();

        // Mengambil semua kategori beserta jumlah produk di dalamnya
        $categories = Category::withCount('products')->get();

        // Mengirimkan data ke view
        return view('menu', compact('products', 'categories'));
    }
}