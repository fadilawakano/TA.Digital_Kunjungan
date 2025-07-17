@extends('layouts.guru')

@section('content')
<a href="{{ route('guru.dashboard') }}" 
   class="inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg shadow transition">
    {{-- Ikon Dashboard --}}
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 12l9-9 9 9M4 10v10a1 1 0 001 1h6m8-11v10a1 1 0 01-1 1h-6" />
    </svg>
</a>

<div class="p-6">
    <h1 class="text-2xl font-bold mb-6 text-gray-700">Dashboard Guru</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
        @php
            $available = [];

            foreach ($bidangStudi as $bidang) {
                $bidangLower = strtolower($bidang);
                if ($bidangLower === 'biologi') {
                    $available[] = [
                        'label' => 'LAB BIOLOGI',
                        'bg' => 'bg-[#FCE7C8]/80',
                        'icon' => '🧬',
                        'route' => route('guru.kunjungan.biologi')
                    ];
                } elseif ($bidangLower === 'fisika') {
                    $available[] = [
                        'label' => 'LAB FISIKA',
                        'bg' => 'bg-[#F8CBA6]/80',
                        'icon' => '🔬',
                        'route' => route('guru.kunjungan.fisika')
                    ];
                } elseif ($bidangLower === 'kimia') {
                    $available[] = [
                        'label' => 'LAB KIMIA',
                        'bg' => 'bg-[#F9AFAE]/80',
                        'icon' => '⚗️',
                        'route' => route('guru.kunjungan.kimia')
                    ];
                }
            }

            // Semua guru bisa akses perpustakaan
            $available[] = [
                'label' => 'PERPUSTAKAAN',
                'bg' => 'bg-[#EEC4C4]/80',
                'icon' => '📚',
                'route' => route('guru.kunjungan.perpustakaan')
            ];
        @endphp

        @foreach ($available as $loc)
    @php
        $kategori = strtolower($loc['label']); // LAB KIMIA => lab kimia
        $kategori = str_replace('lab ', '', $kategori); // lab kimia => kimia
        if ($kategori === 'perpustakaan') $kategori = 'perpustakaan';
        $blokir = in_array($kategori, $kerusakanList ?? []);
    @endphp

    @if ($blokir)
        <div onclick="alert('Anda tidak dapat mengakses {{ $loc['label'] }} karena masih ada laporan kerusakan yang belum dikonfirmasi.')"
             class="cursor-not-allowed opacity-50 {{ $loc['bg'] }} text-gray-800 rounded-3xl p-6 shadow-2xl backdrop-blur-md block border border-white/30">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold">{{ $loc['label'] }}</h2>
                    <p class="text-sm mt-1 text-gray-600">Akses diblokir</p>
                </div>
                <div class="text-4xl">{{ $loc['icon'] }}</div>
            </div>
        </div>
    @else
        <a href="{{ $loc['route'] }}"
           class="cursor-pointer {{ $loc['bg'] }} text-gray-800 rounded-3xl p-6 shadow-2xl backdrop-blur-md hover:scale-105 transition-transform duration-200 ease-in-out block border border-white/30">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold">{{ $loc['label'] }}</h2>
                    <p class="text-sm mt-1 text-gray-600">Klik untuk daftar</p>
                </div>
                <div class="text-4xl">{{ $loc['icon'] }}</div>
            </div>
        </a>
    @endif
@endforeach
    </div>
</div>
@endsection
