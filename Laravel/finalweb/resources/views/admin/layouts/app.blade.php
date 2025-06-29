{-- File: resources/views/admin/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>

</head>
<body class="font-sans antialiased">
    <main class="flex w-full bg-slate-50">

        <aside class="py-6 w-[240px] bg-white h-screen fixed top-0 left-0 z-20 border-r border-slate-200">
            <div class="flex h-full items-center justify-between flex-col w-full">
                <div class="w-full text-center">
                    <a href="/admin/dashboard" class="font-extrabold text-lg">
                        <span class="text-red-500">Tao</span>Bun 🍜
                    </a>
                    <section class="mt-8 flex flex-col gap-y-2">
                        <div class="relative px-6">
                            <a href="/admin/dashboard" class="p-3 group items-center flex w-full rounded-md {{ request()->is('admin/dashboard*') ? 'bg-red-500 text-white shadow' : 'text-gray-700 hover:bg-red-50' }}">
                                <i class="ri-dashboard-line mr-4 text-lg"></i>
                                <span class="font-semibold">Dashboard</span>
                            </a>
                        </div>
                        <div class="relative px-6">
                            <a href="{{ route('dashboard.products') }}" class="p-3 group items-center flex w-full rounded-md {{ request()->routeIs('dashboard.products*') ? 'bg-red-500 text-white shadow' : 'text-gray-700 hover:bg-red-50' }}">
                                <i class="ri-microsoft-line mr-4 text-lg"></i>
                                <span class="font-semibold">Products</span>
                            </a>
                        </div>
                        <div class="relative px-6">
                            <a href="{{ route('dashboard.orders') }}" class="p-3 group items-center flex w-full rounded-md {{ request()->routeIs('dashboard.orders*') ? 'bg-red-500 text-white shadow' : 'text-gray-700 hover:bg-red-50' }}">
                                <i class="ri-clipboard-line mr-4 text-lg"></i>
                                <span class="font-semibold">Orders</span>
                            </a>
                        </div>
                    </section>
                </div>
                <div class="w-full px-6">
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="p-3 flex w-full items-center rounded-md text-red-500 hover:bg-red-50">
                            <i class="ri-logout-circle-line mr-4 text-lg"></i>
                            <span class="font-semibold">Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- Area Konten Utama --}}
        <div class="ml-[240px] w-full">

            {{-- Navbar --}}
<nav class="h-[70px] flex py-4 px-7 justify-between items-center bg-white fixed left-[240px] right-0 top-0 z-10 border-b">
    <input type="text" placeholder="Search" class="px-4 w-96 h-10 py-2 bg-gray-100 rounded-3xl border border-gray-300 focus:outline-red-500" />
    @auth('admin')
    {{-- Tambahkan tag <a> yang membungkus info profil --}}
    <a href="{{ route('admin.profile.edit') }}" class="flex gap-5 items-center group">
        <div class="overflow-hidden h-10 w-10 rounded-full">
            <img src="https://pbs.twimg.com/media/Gp70SL9WoAANARc.jpg" alt="profile" class="w-full object-cover" />
        </div>
        <div>
            <p class="font-bold text-gray-800 group-hover:text-red-500 transition-colors">{{ Auth::guard('admin')->user()->name }}</p>
            <p class="text-sm text-gray-500">Admin</p>
        </div>
    </a>
    @endauth
</nav>

            {{-- Slot untuk konten dari setiap halaman --}}
            <div class="p-7 min-h-screen mt-[70px]">
                @yield('content')
            </div>
        </div>
    </main>
    
    @stack('scripts')
</body>
</html>