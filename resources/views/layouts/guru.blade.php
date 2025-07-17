<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Guru</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/alpinejs" defer></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-cover bg-no-repeat bg-center min-h-screen font-sans antialiased text-gray-800"
      style="background-image: url('{{ asset('img/background.png') }}');">

<div x-data="{ open: false }" class="flex min-h-screen">

    <!-- SIDEBAR -->
    <aside :class="open ? 'translate-x-0' : '-translate-x-full'"
           class="fixed md:static z-40 w-64 bg-[#CDE8E5] text-[#2E3840] md:translate-x-0 transform transition duration-300 ease-in-out border-r border-white/30 shadow-xl">
        <div class="p-6 flex flex-col h-full">
            <div class="flex flex-col items-center mb-6">
                <img src="{{ asset('img/tutwuri handayani.png') }}" class="w-20 h-20 rounded-full border-2 border-white shadow mb-3">
                 <h2 class="text-sm font-semibold text-center text-[#2E3840] truncate">{{ Auth::user()->name }}</h2>
            </div>

            <nav class="flex-1 space-y-2 text-sm">
                <a href="{{ route('guru.dashboard') }}"
                   class="flex items-center gap-3 px-4 py-2 rounded-xl hover:bg-white/20 transition">
                    <i data-lucide="home" class="w-5 h-5"></i> Dashboard
                </a>
                <a href="{{ route('guru.profil.edit') }}"
                   class="flex items-center gap-3 px-4 py-2 rounded-xl hover:bg-white/20 transition">
                    <i data-lucide="user" class="w-5 h-5"></i> Profil
                </a>
                <a href="{{ route('guru.status-verifikasi') }}"
                   class="flex items-center gap-3 px-4 py-2 rounded-xl hover:bg-white/20 transition">
                    <i data-lucide="check-circle" class="w-5 h-5"></i> Status Verifikasi
                </a>
            </nav>

            <form action="{{ route('logout') }}" method="POST" class="pt-4 border-t border-white/30 mt-auto">
                @csrf
                <button type="submit"
                        class="flex items-center gap-3 px-4 py-2 rounded-xl w-full text-left hover:bg-red-500/70 transition">
                    <i data-lucide="log-out" class="w-5 h-5"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- OVERLAY (untuk HP) -->
    <div x-show="open" @click="open = false" class="fixed inset-0 bg-black/40 z-30 md:hidden"></div>

    <!-- KONTEN UTAMA -->
    <div class="flex-1 flex flex-col min-h-screen">
        <!-- TOPBAR MOBILE -->
        <header class="md:hidden flex items-center justify-between px-4 py-3 bg-white/10 backdrop-blur border-b border-white/20 text-white">
            <button @click="open = true"><i data-lucide="menu" class="w-6 h-6"></i></button>
            <h1 class="text-base font-semibold">Dashboard</h1>
            <div></div>
        </header>

        <!-- KONTEN -->
        <main class="flex-1 overflow-y-auto p-4 md:p-8">
            @yield('content')
        </main>
    </div>
</div>

<script>lucide.createIcons();</script>
</body>
</html>
