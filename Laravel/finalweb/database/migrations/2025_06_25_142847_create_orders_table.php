<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // Kolom ini akan terhubung ke tabel 'transactions'
            $table->foreignId('transaction_id')->constrained()->onDelete('cascade');

            // Kolom ini akan terhubung ke tabel 'products'
            $table->foreignId('product_id')->constrained()->onDelete('cascade');

            // Menyimpan status pembayaran, contoh: 'pending', 'paid', 'failed'
            $table->string('payment_status')->default('pending');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};