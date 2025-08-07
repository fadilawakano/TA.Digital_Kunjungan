@extends('layouts.murid')

@section('content')
<a href="{{ route('murid.dashboard') }}" 
   class="inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg shadow transition">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 12l9-9 9 9M4 10v10a1 1 0 001 1h6m8-11v10a1 1 0 01-1 1h-6" />
    </svg>
</a>

<div class="p-6">
    <h1 class="text-2xl font-bold mb-6 text-gray-700">Dashboard Murid</h1>

    @if (session('error'))
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
        @php
            $locations = [
                ['key' => 'biologi', 'label' => 'LAB BIOLOGI', 'bg' => 'bg-[#FCE7C8]/80', 'icon' => '🧬', 'route' => route('murid.kunjungan.biologi')],
                ['key' => 'fisika', 'label' => 'LAB FISIKA', 'bg' => 'bg-[#F8CBA6]/80', 'icon' => '🔬', 'route' => route('murid.kunjungan.fisika')],
                ['key' => 'kimia', 'label' => 'LAB KIMIA', 'bg' => 'bg-[#F9AFAE]/80', 'icon' => '⚗️', 'route' => route('murid.kunjungan.kimia')],
                ['key' => 'perpustakaan', 'label' => 'PERPUSTAKAAN', 'bg' => 'bg-[#EEC4C4]/80', 'icon' => '📚', 'route' => route('murid.kunjungan.perpustakaan')],
            ];
        @endphp

        @foreach ($locations as $loc)
    @php
        $jadwalKunjungan = is_object($jadwal[$loc['key']]) ? $jadwal[$loc['key']] : null;
        $now = \Carbon\Carbon::now();
        $waktuMulai = $jadwalKunjungan?->waktu_mulai ? \Carbon\Carbon::parse($jadwalKunjungan->waktu_mulai) : null;
    @endphp

    {{-- PERPUSTAKAAN (tidak tergantung jadwal) --}}
    @if ($loc['key'] === 'perpustakaan')
        <a href="{{ $loc['route'] }}"
           class="cursor-pointer {{ $loc['bg'] }} text-gray-800 rounded-3xl p-6 shadow-2xl hover:scale-105 transition-transform duration-200 ease-in-out block border border-white/30">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold">{{ $loc['label'] }}</h2>
                    <p class="text-sm mt-1 text-gray-600">Klik untuk daftar</p>
                </div>
                <div class="text-4xl">{{ $loc['icon'] }}</div>
            </div>
        </a>

    {{-- LAB (biologi, fisika, kimia) --}}
    @elseif ($jadwalKunjungan)
        @if ($waktuMulai && $waktuMulai->isFuture())
            {{-- Jadwal ada, tapi belum dimulai --}}
            <div class="bg-yellow-100 text-yellow-800 rounded-3xl p-6 shadow-inner border border-yellow-300 cursor-not-allowed">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-bold">{{ $loc['label'] }}</h2>
                        <p class="text-sm mt-1">Kunjungan akan dibuka pada {{ $waktuMulai->translatedFormat('l, d F Y H:i') }}</p>
                    </div>
                    <div class="text-4xl opacity-60">{{ $loc['icon'] }}</div>
                </div>
            </div>
        @else
            {{-- Jadwal ada dan sudah aktif --}}
            <a href="{{ $loc['route'] }}"
               class="cursor-pointer {{ $loc['bg'] }} text-gray-800 rounded-3xl p-6 shadow-2xl hover:scale-105 transition-transform duration-200 ease-in-out block border border-white/30">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-bold">{{ $loc['label'] }}</h2>
                        <p class="text-sm mt-1 text-gray-600">Klik untuk daftar</p>
                    </div>
                    <div class="text-4xl">{{ $loc['icon'] }}</div>
                </div>
            </a>
        @endif

    {{-- Tidak ada jadwal untuk lab --}}
    @else
        <div class="bg-gray-200 text-gray-500 rounded-3xl p-6 shadow-inner border border-gray-300 cursor-not-allowed">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold">{{ $loc['label'] }}</h2>
                    <p class="text-sm mt-1">Tidak tersedia untuk kelas Anda hari ini</p>
                </div>
                <div class="text-4xl opacity-40">{{ $loc['icon'] }}</div>
            </div>
        </div>
    @endif
@endforeach
    </div>
</div>
@endsection
