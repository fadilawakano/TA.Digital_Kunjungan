<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin | SMAN 15</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
            <div class="text-lg font-bold text-white">ADMIN PANEL</div>
            <button @click="sidebarOpen = !sidebarOpen" class="text-black text-2xl focus:outline-none">
                ☰
            </button>
        </header>

        <!-- Sidebar -->
        <aside
            class="fixed inset-y-0 left-0 w-64 bg-[#7EACB5]/80 backdrop-blur-xl text-white shadow-xl transform transition-transform duration-300 ease-in-out z-40 md:static md:translate-x-0 md:block overflow-y-auto max-h-screen"
            :class="{ '-translate-x-full': !sidebarOpen }"
        >
            <div class="p-6 border-b border-[#A4907C] text-center">
                <img src="{{ asset('img/tutwuri handayani.png') }}" alt="Tut Wuri Handayani" class="w-20 mx-auto mb-3">
                <h2 class="text-xl font-bold text-black">ADMIN</h2>
                <p class="text-sm text-black">SMAN 15</p>
            </div>

            <nav class="px-4 py-6">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('admin.dashboard') }}"
                           class="flex items-center px-4 py-2 rounded-lg transition font-medium
                           {{ request()->routeIs('admin.dashboard') ? 'bg-[#B4B4B8] text-white' : 'text-black hover:bg-[#C7C8CC]' }}">
                            <svg class="h-5 w-5 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-black' }}"
                                 fill="none" stroke="currentColor" stroke-width="2"
                                 viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M3 12l9-9 9 9v7a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                            </svg>
                            <span class="ml-3">Dashboard Ringkasan</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.users.index') }}"
                           class="flex items-center px-4 py-2 rounded-lg transition font-medium
                           {{ request()->routeIs('admin.users.index') ? 'bg-[#B4B4B8] text-white' : 'text-black hover:bg-[#C7C8CC]' }}">
                            <svg class="h-5 w-5 {{ request()->routeIs('admin.users.index') ? 'text-white' : 'text-black' }}"
                                 fill="none" stroke="currentColor" stroke-width="2"
                                 viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M17 20h5v-2a4 4 0 00-3-3.87M9 20h6M3 20h5v-2a4 4 0 00-3-3.87M16 4a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <span class="ml-3">Manajemen Pengguna</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.kunjungan.index') }}"
                           class="flex items-center px-4 py-2 rounded-lg transition font-medium
                           {{ request()->routeIs('admin.kunjungan.index') ? 'bg-[#B4B4B8] text-white' : 'text-black hover:bg-[#C7C8CC]' }}">
                            <svg class="h-5 w-5 {{ request()->routeIs('admin.kunjungan.index') ? 'text-white' : 'text-black' }}"
                                 fill="none" stroke="currentColor" stroke-width="2"
                                 viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M9.75 3a2.25 2.25 0 00-2.25 2.25v1.5A2.25 2.25 0 009.75 9h4.5A2.25 2.25 0 0016.5 6.75v-1.5A2.25 2.25 0 0014.25 3h-4.5zM3 6.75A3.75 3.75 0 016.75 3h10.5A3.75 3.75 0 0121 6.75v10.5A3.75 3.75 0 0117.25 21H6.75A3.75 3.75 0 013 17.25V6.75z"/>
                            </svg>
                            <span class="ml-3">Manajemen Kunjungan</span>
                        </a>
                    </li>

                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="w-full flex items-center px-4 py-2 text-left rounded-lg transition hover:bg-red-100 text-red-500 font-medium">
                                <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" stroke-width="2"
                                     viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 002 2h3a2 2 0 002-2V7a2 2 0 00-2-2h-3a2 2 0 00-2 2v1"/>
                                </svg>
                                <span class="ml-3">Logout</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Overlay (Mobile Only) -->
        <div
            class="fixed inset-0 bg-black bg-opacity-50 z-30 md:hidden"
            x-show="sidebarOpen"
            x-transition.opacity
            @click="sidebarOpen = false"
        ></div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col max-h-screen overflow-y-auto bg-transparent">
            <main class="flex-1 p-6 overflow-visible">
                @yield('content')
            </main>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
