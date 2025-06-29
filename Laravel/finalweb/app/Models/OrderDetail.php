<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'product_id',
        'nama_produk',
        'jumlah',
        'harga',
    ];

    /**
     * Mendefinisikan bahwa setiap detail pesanan milik satu 'Order'.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Mendefinisikan bahwa setiap detail pesanan terkait dengan satu 'Product'.
     * Relasi ini opsional tapi sangat berguna.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}