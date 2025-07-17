@extends('layouts.laboratorium')

@section('content')

<a href="{{ route('biologi.dashboard') }}" 
   class="inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg shadow transition">
    {{-- Ikon Dashboard --}}
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 12l9-9 9 9M4 10v10a1 1 0 001 1h6m8-11v10a1 1 0 01-1 1h-6" />
    </svg>
</a>


<div class="p-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">🧪 Dashboard Lab Biologi</h1>

    <!-- Info Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
        <div class="bg-gradient-to-br from-green-100 to-green-200 p-6 rounded-2xl shadow-inner border border-green-300">
            <h2 class="text-lg font-semibold text-gray-700">Kunjungan Bulan Minggu Ini</h2>
            <p class="text-4xl font-bold text-green-700 mt-2">{{ $muridHariIni }}</p>
        </div>

        <div class="bg-gradient-to-br from-blue-100 to-blue-200 p-6 rounded-2xl shadow-inner border border-blue-300">
            <h2 class="text-lg font-semibold text-gray-700">Kunjungan Guru Bulan Ini</h2>
            <p class="text-4xl font-bold text-blue-700 mt-2">{{ $guruHariIni }}</p>
        </div>
    </div>

    <!-- Diagram Status Donut dan Tren Mingguan -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Donut Chart Verifikasi -->
        <div class="bg-white p-6 rounded-2xl shadow-md">
    <h2 class="text-lg font-semibold mb-4 text-gray-700">Status Verifikasi Kunjungan</h2>

            @if(array_sum($statusVerifikasi) === 0)
                <p class="text-center text-sm text-gray-500 mt-10">Belum ada data verifikasi berhasil atau rusak.</p>
            @else
                <canvas id="statusChart"></canvas>
            @endif
        </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    @if(array_sum($statusVerifikasi) > 0)
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'bar',
        data: {
            labels: ['Berhasil', 'Rusak'],
            datasets: [
                {
                    label: 'Murid',
                    data: [{{ $statusVerifikasi['murid_berhasil'] }}, {{ $statusVerifikasi['murid_rusak'] }}],
                    backgroundColor: '#60A5FA',
                    stack: 'murid'
                },
                {
                    label: 'Guru',
                    data: [{{ $statusVerifikasi['guru_berhasil'] }}, {{ $statusVerifikasi['guru_rusak'] }}],
                    backgroundColor: '#FBBF24',
                    stack: 'guru'
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            },
            scales: {
                x: {
                    stacked: true,
                },
                y: {
                    stacked: true,
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
    @endif
    </script>
</div>
@endsection
