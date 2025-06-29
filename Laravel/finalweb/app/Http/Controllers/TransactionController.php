<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function checkout(Request $request)
    {
        $request->validate(['cart' => 'required|json']);
        $cart = json_decode($request->cart, true);

        if (empty($cart)) {
            return back()->with('error', 'Keranjang Anda kosong!');
        }

        $request->session()->put('cart', $cart);
        return redirect()->route('payment.page');
    }
    
    public function paymentPage(Request $request)
    {
        $cart = $request->session()->get('cart');
        if (!$cart) {
            return redirect()->route('menu')->with('error', 'Keranjang tidak ditemukan.');
        }

        $totalPrice = 0;
        foreach ($cart as $id => $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }
        return view('payment', compact('cart', 'totalPrice'));
    }


    /**
     * Memproses pesanan setelah pembayaran dikonfirmasi.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
        ]);

        $cart = $request->session()->get('cart');
        if (!$cart) {
            return redirect()->route('menu')->with('error', 'Sesi pesanan habis. Silakan coba lagi.');
        }

        DB::beginTransaction();
        try {
            $totalPrice = 0;
            foreach ($cart as $id => $item) {
                $product = Product::find($id);
                if ($product->stock < $item['quantity']) {
                    throw new \Exception('Stok untuk produk ' . $product->name . ' tidak mencukupi.');
                }
                $product->stock -= $item['quantity'];
                $product->save();
                $totalPrice += $product->price * $item['quantity'];
            }
            
            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'total_price' => $totalPrice,
                'payment_method' => 'QRIS',
                'status' => 'diproses',
            ]);

            foreach ($cart as $id => $item) {
                Order::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $id,
                    'payment_status' => 'paid',
                    'quantity' => $item['quantity'],
                ]);
            }
            
            DB::commit();
            $request->session()->forget('cart');
            
            // Mengarahkan ke halaman sukses
            return redirect()->route('order.success', $transaction->id)
                             ->with('success', 'Pesanan Anda telah berhasil dibuat dan sedang diproses!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('payment.page')->with('error', $e->getMessage());
        }
    }

    /**
     * Menampilkan halaman sukses.
     */
    public function success(Transaction $transaction)
    {
        return view('success', compact('transaction'));
    }
}
