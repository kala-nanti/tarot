<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>TaoBun 🍜 - Pesan Sekarang</title>
    {{-- Impor Tailwind & Remix Icon dengan sintaks HTML yang benar --}}
    <script src="https://cdn.tailwindcss.com"></script>
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet" />
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

    <style>
        body { font-family: 'Figtree', sans-serif; }
        /* Custom scrollbar for webkit browsers */
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #ef4444;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #dc2626;
        }
    </style>
</head>
<body class="bg-gray-100">
    
    @yield('content')

    @stack('scripts')
</body>
</html>