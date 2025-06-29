<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'transaction_id',
        'product_id',
        'payment_status',
        'quantity',
    ];

    /**
     * Mendefinisikan bahwa setiap Order (detail pesanan) milik satu Transaction.
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * Mendefinisikan bahwa setiap Order terkait dengan satu Product.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
