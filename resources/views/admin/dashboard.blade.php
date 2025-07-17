@extends('layouts.admin')

@section('content')
<a href="{{ route('admin.dashboard') }}" 
   class="inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg shadow transition">
    {{-- Ikon Dashboard --}}
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 12l9-9 9 9M4 10v10a1 1 0 001 1h6m8-11v10a1 1 0 01-1 1h-6" />
    </svg>
</a>

<div class="p-6 space-y-8">
    <h1 class="text-2xl font-bold">Dashboard Admin</h1>

    {{-- Chart Total Kunjungan Hari Ini, Mingguan, Bulanan --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl shadow p-4">
            <h2 class="font-semibold mb-2">Total Kunjungan Hari Ini</h2>
            <canvas id="kunjunganHariIni"></canvas>
        </div>
        <div class="bg-white rounded-xl shadow p-4">
            <h2 class="font-semibold mb-2">Total Kunjungan Minggu Ini</h2>
            <canvas id="kunjunganMingguan"></canvas>
        </div>
        <div class="bg-white rounded-xl shadow p-4">
            <h2 class="font-semibold mb-2">Total Kunjungan Bulan Ini</h2>
            <canvas id="kunjunganBulanan"></canvas>
        </div>
    </div> 

    {{-- Row 1: Kunjungan per Lokasi --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
    <div class="bg-white rounded-xl shadow p-4">
        <h2 class="font-semibold mb-2">Kunjungan Murid per Lokasi</h2>
        <canvas id="lokasiMurid" class="mx-auto w-40 h-40"></canvas>
    </div>
    <div class="bg-white rounded-xl shadow p-4">
        <h2 class="font-semibold mb-2">Kunjungan Guru per Lokasi</h2>
        <canvas id="lokasiGuru" class="mx-auto w-40 h-40"></canvas>
    </div>
</div>

<div class="bg-white p-4 rounded-xl shadow mt-6">
    <h2 class="text-lg font-bold mb-2">Status Verifikasi per Lokasi (Gabungan Murid & Guru)</h2>
    <canvas id="chartGabungan"></canvas>
</div>



{{-- ChartJS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const totalHariIni = {{ ($hariIni['murid'] ?? 0) + ($hariIni['guru'] ?? 0) }};
    const totalMingguan = {{ ($mingguan['murid'] ?? 0) + ($mingguan['guru'] ?? 0) }};
    const totalBulanan = {{ ($bulanan['murid'] ?? 0) + ($bulanan['guru'] ?? 0) }};

    new Chart(document.getElementById('kunjunganHariIni'), {
        type: 'bar',
        data: {
            labels: ['Murid', 'Guru'],
            datasets: [{
                label: 'Hari Ini',
                data: [{{ $hariIni['murid'] ?? 0 }}, {{ $hariIni['guru'] ?? 0 }}],
                backgroundColor: ['#EFE4D2', '#954C2E']
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });

    new Chart(document.getElementById('kunjunganMingguan'), {
        type: 'bar',
        data: {
            labels: ['Murid', 'Guru'],
            datasets: [{
                label: 'Minggu Ini',
                data: [{{ $mingguan['murid'] ?? 0 }}, {{ $mingguan['guru'] ?? 0 }}],
                backgroundColor: ['#AEC8A4', '#5A827E']
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });

    new Chart(document.getElementById('kunjunganBulanan'), {
        type: 'bar',
        data: {
            labels: ['Murid', 'Guru'],
            datasets: [{
                label: 'Bulan Ini',
                data: [{{ $bulanan['murid'] ?? 0 }}, {{ $bulanan['guru'] ?? 0 }}],
                backgroundColor: ['#708871', '#2C3930']
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });

    // Doughnut Chart - Murid
    new Chart(document.getElementById('lokasiMurid'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode(array_keys($lokasiMurid ?? [])) !!},
            datasets: [{
                label: 'Jumlah Kunjungan',
                data: {!! json_encode(array_values($lokasiMurid ?? [])) !!},
                backgroundColor: ['#4793AF', '#FFC470', '#DD5746', '#8B322C']
            }]
        }
    });

    // Doughnut Chart - Guru
    new Chart(document.getElementById('lokasiGuru'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode(array_keys($lokasiGuru ?? [])) !!},
            datasets: [{
                label: 'Jumlah Kunjungan',
                data: {!! json_encode(array_values($lokasiGuru ?? [])) !!},
                backgroundColor: ['#B0C5A4', '#D37676', '#EBC49F', '#F1EF99']
            }]
        }
    });

    // Gabungan Bar Chart
    new Chart(document.getElementById('chartGabungan'), {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_column($statusGabungan ?? [], 'lokasi')) !!},
            datasets: [
                {
                    label: 'Murid - Berhasil',
                    data: {!! json_encode(array_column($statusGabungan ?? [], 'murid_berhasil')) !!},
                    backgroundColor: '#FDFFAB'
                },
                {
                    label: 'Murid - Kerusakan',
                    data: {!! json_encode(array_column($statusGabungan ?? [], 'murid_rusak')) !!},
                    backgroundColor: '#982B1C'
                },
                {
                    label: 'Guru - Berhasil',
                    data: {!! json_encode(array_column($statusGabungan ?? [], 'guru_berhasil')) !!},
                    backgroundColor: '#D5E7B5'
                },
                {
                    label: 'Guru - Kerusakan',
                    data: {!! json_encode(array_column($statusGabungan ?? [], 'guru_rusak')) !!},
                    backgroundColor: '#7C444F'
                },
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' }
            },
            scales: {
                x: {
                    stacked: false,
                    title: {
                        display: true,
                        text: 'Lokasi'
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah Verifikasi'
                    },
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
</script>
@endsection
