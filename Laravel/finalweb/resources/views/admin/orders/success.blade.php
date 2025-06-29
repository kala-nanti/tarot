<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Berhasil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 600px;
            margin-top: 50px;
        }
        .card {
            border: 1px solid #28a745;
        }
        .card-header {
            background-color: #28a745;
            color: white;
            font-weight: bold;
            text-align: center;
        }
        .order-details, .payment-instructions {
            margin-top: 20px;
        }
        .list-group-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3>✔ Pesanan Anda Berhasil Dibuat!</h3>
            </div>
            <div class="card-body">

                {{-- Menampilkan pesan sukses dari controller --}}
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                
                <p class="text-center">Terima kasih telah memesan. Silakan selesaikan pembayaran Anda.</p>

                <div class="order-details">
                    <h5>Detail Pesanan</h5>
                    <ul class="list-group">
                        <li class="list-group-item">
                            <strong>ID Pesanan:</strong>
                            <span>#{{ $order->id }}</span>
                        </li>
                        <li class="list-group-item">
                            <strong>Tanggal:</strong>
                            <span>{{ $order->created_at->format('d F Y, H:i') }}</span>
                        </li>
                        <li class="list-group-item">
                            <strong>Status:</strong>
                            <span class="badge bg-warning text-dark">{{ ucwords($order->status) }}</span>
                        </li>
                        <li class="list-group-item">
                            <strong>Nama Pelanggan:</strong>
                            <span>{{ $order->nama_pelanggan }}</span>
                        </li>
                    </ul>
                </div>

                <div class="order-items mt-4">
                    <h5>Item yang Dipesan</h5>
                    <table class="table">
                        <tbody>
                            @foreach($order->details as $detail)
                            <tr>
                                <td>{{ $detail->nama_produk }} ({{ $detail->jumlah }}x)</td>
                                <td class="text-end">Rp {{ number_format($detail->harga * $detail->jumlah, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                            <tr class="fw-bold">
                                <td>Total Pembayaran</td>
                                <td class="text-end">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="payment-instructions alert alert-info">
                    <h5>Instruksi Pembayaran</h5>
                    <p>Silakan lakukan pembayaran melalui transfer ke salah satu rekening berikut:</p>
                    <ul>
                        <li><strong>Bank BCA:</strong> 1234-5678-90 a/n Restoran Keren</li>
                        <li><strong>Bank Mandiri:</strong> 098-765-4321 a/n Restoran Keren</li>
                    </ul>
                    <p>Mohon lakukan konfirmasi setelah pembayaran agar pesanan Anda dapat segera kami proses.</p>
                </div>

                <div class="text-center mt-4">
                    <a href="/" class="btn btn-primary">Kembali ke Beranda</a>
                </div>

            </div>
        </div>
    </div>
</body>
</html>