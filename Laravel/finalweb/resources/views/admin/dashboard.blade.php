@extends('admin.layouts.app')

@section('content')
    <h1 class="text-3xl font-bold mb-6">Dashboard Admin</h1>
    <p class="text-lg mb-6">Selamat datang, {{ Auth::guard('admin')->user()->name }}!</p>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 mb-10">
        <div class="bg-white p-4 rounded-xl shadow flex flex-col justify-between h-36">
            <div class="flex justify-between items-start">
                <span class="font-semibold text-gray-600">Total Drink</span>
                <div class="p-3 bg-pink-100 rounded-full"><i class="ri-goblet-line text-2xl text-pink-600"></i></div>
            </div>
            <p class="text-3xl font-bold text-right">{{ $totalDrink }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl shadow flex flex-col justify-between h-36">
             <div class="flex justify-between items-start">
                <span class="font-semibold text-gray-600">Total Food</span>
                <div class="p-3 bg-blue-100 rounded-full"><i class="ri-restaurant-line text-2xl text-blue-600"></i></div>
            </div>
            <p class="text-3xl font-bold text-right">{{ $totalFood }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl shadow flex flex-col justify-between h-36">
            <div class="flex justify-between items-start">
                <span class="font-semibold text-gray-600">Total Order</span>
                <div class="p-3 bg-orange-100 rounded-full"><i class="ri-shopping-cart-2-line text-2xl text-orange-600"></i></div>
            </div>
            <p class="text-3xl font-bold text-right">{{ $totalOrders }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl shadow flex flex-col justify-between h-36">
            <div class="flex justify-between items-start">
                <span class="font-semibold text-gray-600">Total Income</span>
                 <div class="p-3 bg-green-100 rounded-full"><i class="ri-money-dollar-circle-line text-2xl text-green-600"></i></div>
            </div>
            <p class="text-3xl font-bold text-right">Rp.{{ number_format($totalIncome, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow">
        <h2 class="text-xl font-bold mb-4">Grafik Pendapatan (7 Hari Terakhir)</h2>
        <div class="relative h-96">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart');
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: '{!! json_encode($chartLabels) !!}',
            datasets: [{
                label: 'Pendapatan',
                data: '{!! json_encode($chartData) !!}',
                fill: true,
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.y);
                            }
                            return label;
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
