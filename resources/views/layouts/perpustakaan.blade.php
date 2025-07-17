<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Perpustakaan | SMAN 15')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body {
            background-image: url('/img/background.png');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        }
    </style>
</head>
<body class="font-sans text-gray-800" x-data="{ sidebarOpen: false }">

<div class="min-h-screen flex flex-col md:flex-row bg-opacity-80 backdrop-blur-md relative overflow-hidden">

    <!-- Mobile Header -->
    <header class="md:hidden flex justify-between items-center p-4 bg-[#7EACB5]/70 text-white">
        <div class="text-lg font-bold text-white">PERPUSTAKAAN</div>
        <button @click="sidebarOpen = !sidebarOpen" class="text-black text-2xl focus:outline-none">☰</button>
    </header>

    <!-- Sidebar -->
    <aside
        class="fixed inset-y-0 left-0 w-64 bg-[#7EACB5]/80 backdrop-blur-xl text-white shadow-xl transform transition-transform duration-300 ease-in-out z-40 md:static md:translate-x-0 md:block overflow-y-auto max-h-screen"
        :class="{ '-translate-x-full': !sidebarOpen }"
    >
        <!-- Logo & Header -->
        <div class="p-6 border-b border-[#A4907C] text-center">
            <div class="w-20 h-20 mx-auto mb-3 rounded-full border-4 border-white shadow-lg bg-white overflow-hidden">
                <img src="{{ asset('img/tutwuri handayani.png') }}"
                     alt="Tut Wuri Handayani"
                     class="w-full h-full object-contain p-1">
            </div>
            <h2 class="text-xl font-bold text-[#2E3840] uppercase tracking-wide">Perpustakaan</h2>
            <p class="text-sm text-gray-700">SMAN 15</p>
        </div>

        <!-- Navigation -->
        <nav class="px-4 py-6 text-sm font-medium">
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('perpustakaan.dashboard') }}"
                       class="flex items-center gap-3 px-4 py-2 rounded-lg transition
                       {{ request()->routeIs('perpustakaan.dashboard') ? 'bg-white/20 text-white' : 'text-white hover:bg-white/10' }}">
                        <i data-lucide="home" class="w-5 h-5"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('perpustakaan.murid') }}"
                       class="flex items-center gap-3 px-4 py-2 rounded-lg transition
                       {{ request()->routeIs('perpustakaan.murid') ? 'bg-white/20 text-white' : 'text-white hover:bg-white/10' }}">
                        <i data-lucide="users" class="w-5 h-5"></i> Kunjungan Murid
                    </a>
                </li>
                <li>
                    <a href="{{ route('perpustakaan.guru') }}"
                       class="flex items-center gap-3 px-4 py-2 rounded-lg transition
                       {{ request()->routeIs('perpustakaan.guru') ? 'bg-white/20 text-white' : 'text-white hover:bg-white/10' }}">
                        <i data-lucide="book-open" class="w-5 h-5"></i> Peminjaman Guru
                    </a>
                </li>
                <li>
                    <a href="{{ route('perpustakaan.profil.edit') }}"
                       class="flex items-center gap-3 px-4 py-2 rounded-lg transition
                       {{ request()->routeIs('perpustakaan.profil.edit') ? 'bg-white/20 text-white' : 'text-white hover:bg-white/10' }}">
                        <i data-lucide="user" class="w-5 h-5"></i> Profil
                    </a>
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="w-full flex items-center gap-3 px-4 py-2 text-left rounded-lg transition hover:bg-red-100 text-red-500 font-medium">
                            <i data-lucide="log-out" class="w-5 h-5"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Overlay for mobile -->
    <div
        class="fixed inset-0 bg-black bg-opacity-50 z-30 md:hidden"
        x-show="sidebarOpen"
        x-transition.opacity
        @click="sidebarOpen = false"
    ></div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col max-h-screen overflow-y-auto bg-transparent">
        <main class="flex-1 p-6">

            <!-- Flash Messages -->
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-800 p-3 rounded mb-4 shadow">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-800 p-3 rounded mb-4 shadow">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>lucide.createIcons();</script>
</body>
</html>
