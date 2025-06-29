<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Menampilkan daftar semua transaksi atau pesanan kepada admin.
     */
    public function index()
    {
        
        $orders = Transaction::latest()->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    public function update(Request $request, Transaction $order)
    {
        // Validasi input status menggunakan istilah yang ada di dropdown.
        $request->validate([
            'status' => 'required|in:diproses,selesai,dibatalkan',
        ]);

        // Mengupdate status transaksi yang dipilih.
        $order->update([
            'status' => $request->status,
        ]);

        // Kembali ke halaman daftar pesanan dengan pesan sukses.
        return redirect()->route('admin.orders.index')->with('success', 'Status pesanan #' . $order->id . ' berhasil diperbarui.');
    }
}
