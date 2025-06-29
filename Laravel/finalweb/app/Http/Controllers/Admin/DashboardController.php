<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik Kartu Atas
        $totalOrders = Transaction::where('status', '!=', 'dibatalkan')->count();
        $totalIncome = Transaction::where('status', 'selesai')->sum('total_price');
        $totalFood = Order::whereHas('product.category', function ($query) {
            $query->where('name', 'Food');
        })->sum('quantity');
        $totalDrink = Order::whereHas('product.category', function ($query) {
            $query->where('name', 'Drink');
        })->sum('quantity');

        // Logika untuk mengambil data grafik penjualan
        $salesData = Transaction::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('sum(total_price) as total')
        )
        ->where('status', 'selesai')
        ->where('created_at', '>=', Carbon::now()->subDays(7))
        ->groupBy('date')
        ->orderBy('date', 'asc')
        ->get();

        // Memformat data untuk Chart.js
        $chartLabels = $salesData->pluck('date')->map(function ($date) {
            return Carbon::parse($date)->format('d M');
        });
        $chartData = $salesData->pluck('total');

        // Kirim semua data ke view
        return view('admin.dashboard', [
            'totalOrders' => $totalOrders,
            'totalIncome' => $totalIncome,
            'totalFood' => $totalFood,
            'totalDrink' => $totalDrink,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
        ]);
    }
}
