<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart;

class OrderController extends Controller
{
    /**
     * Menyimpan pesanan baru yang dibuat oleh pelanggan dari halaman keranjang.
     * Fungsi ini dipicu saat pelanggan menekan tombol checkout.
     */
    public function storeOrder(Request $request)
    {

        // Pastikan keranjang tidak kosong sebelum membuat pesanan
        if (Cart::count() == 0) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong!');
        }

        // Langkah 1: Buat record baru di tabel 'orders'
        $order = Order::create([
            'user_id' => Auth::id(), // Mendapatkan ID pengguna yang sedang login
            'nama_pelanggan' => $request->input('nama_pelanggan', 'Pelanggan'), // Ambil dari form, atau gunakan nilai default
            'total_harga' => Cart::total(0, '', ''), // Gunakan Cart::total() tanpa format
            'status' => 'diproses', // Status awal saat pesanan dibuat
            'tanggal' => now(),
        ]);

        // Langkah 2: Simpan setiap item di keranjang ke tabel 'order_details'
        foreach (Cart::content() as $item) {
            OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $item->id,
                'nama_produk' => $item->name,
                'jumlah' => $item->qty,
                'harga' => $item->price,
            ]);
        }

        // Langkah 3: Hapus isi keranjang belanja setelah pesanan berhasil dibuat
        Cart::destroy();

        // Langkah 4: Arahkan pelanggan ke halaman sukses dengan membawa ID pesanan
        return redirect()->route('order.success', $order->id)
                         ->with('success', 'Pesanan Anda telah berhasil dibuat!');
    }

    /**
     * Menampilkan halaman konfirmasi setelah pesanan berhasil dibuat.
     */
    public function success(Order $order)
    {
        // Mengirim data pesanan yang spesifik ke view 'orders.success'
        return view('orders.success', [
            'order' => $order->load('details') // Memuat relasi 'details' untuk menampilkan item pesanan
        ]);
    }
}