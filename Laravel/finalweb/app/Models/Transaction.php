<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'total_price',
        'payment_method',
        'status',
    ];

    /**
     * Mendefinisikan relasi ke model User.
     * Satu transaksi dimiliki oleh satu User (pelanggan).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mendefinisikan relasi ke model Order (Order Items).
     * Satu transaksi memiliki banyak item pesanan.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}


