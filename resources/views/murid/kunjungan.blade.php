@extends('layouts.murid')

@section('content')
<a href="{{ route('murid.dashboard') }}"
   class="inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg shadow transition">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 12l9-9 9 9M4 10v10a1 1 0 001 1h6m8-11v10a1 1 0 01-1 1h-6" />
    </svg>
</a>

<div class="bg-[#BEE4D0] p-6 rounded-3xl shadow-2xl max-w-xl mx-auto mt-10 border border-white/30 backdrop-blur-md">
    <h2 class="text-2xl font-semibold text-center mb-6 text-gray-800">
        Form Kunjungan {{ strtoupper($lokasi) }}
    </h2>

    {{-- Alert --}}
    @if (session('success') || session('error'))
        <div 
            x-data="{ show: true }" 
            x-init="setTimeout(() => show = false, 3000)" 
            x-show="show"
            class="fixed inset-0 z-50 flex items-center justify-center pointer-events-none">
            <div class="bg-white rounded-xl p-6 w-full max-w-md text-center border-2 border-gray-200 pointer-events-auto">
                <h2 class="text-xl font-bold {{ session('success') ? 'text-green-600' : 'text-red-600' }}">
                    {{ session('success') ?? session('error') }}
                </h2>
            </div>
        </div>
    @endif

    {{-- Validation errors --}}
    @if ($errors->any())
        <div class="bg-red-100 text-red-800 p-3 rounded mb-4 text-sm">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Warning if ada kerusakan --}}
    @if($kerusakan)
        <div class="bg-red-200 text-red-800 p-3 rounded mb-4 text-sm">
            ⚠️ Anda memiliki laporan kerusakan yang belum dikonfirmasi.
        </div>
    @endif

    <form action="{{ route('murid.kunjungan.store') }}" method="POST" id="form-kunjungan">
        @csrf
        <input type="hidden" name="lokasi" value="{{ strtolower($lokasi) }}">

        {{-- Jika Perpustakaan --}}
        @if (Str::contains(Str::lower($lokasi), 'perpus'))
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Judul Buku</label>
                <input type="text" name="judul_buku" class="w-full border border-gray-300 rounded-xl px-3 py-2 bg-white" value="{{ old('judul_buku') }}">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Jumlah Buku</label>
                <input type="number" name="jumlah_buku" class="w-full border border-gray-300 rounded-xl px-3 py-2 bg-white" value="{{ old('jumlah_buku') }}">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Tipe</label>
                <select name="tipe" id="tipe" class="w-full border border-gray-300 rounded-xl px-3 py-2 bg-white" onchange="toggleForm()">
                    <option value="">-- Pilih Tipe --</option>
                    <option value="baca" {{ old('tipe') == 'baca' ? 'selected' : '' }}>Baca</option>
                    <option value="pinjam" {{ old('tipe') == 'pinjam' ? 'selected' : '' }}>Pinjam</option>
                </select>
            </div>

            <div class="mb-4" id="tanggal-pengembalian-wrapper" style="display: none;">
                <label class="block text-sm font-medium text-gray-700">Tanggal Pengembalian</label>
                <input type="date" name="tanggal_pengembalian" id="tanggal_pengembalian" class="w-full border border-gray-300 rounded-xl px-3 py-2 bg-white" value="{{ old('tanggal_pengembalian') }}">
            </div>

        {{-- Jika Lab --}}
        @else
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Mata Pelajaran</label>
                <input type="text" name="mata_pelajaran" class="w-full border border-gray-300 rounded-xl px-3 py-2 bg-white" value="{{ old('mata_pelajaran') }}">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Judul Materi</label>
                <input type="text" name="judul_materi" class="w-full border border-gray-300 rounded-xl px-3 py-2 bg-white" value="{{ old('judul_materi') }}">
            </div>
        @endif

        <div class="flex justify-end mt-6">
            <button type="submit" class="bg-[#8F87F1] hover:bg-[#BDDDE4] text-white px-5 py-2 rounded-xl shadow-md transition duration-200">
                Kirim
            </button>
        </div>
    </form>
</div>

<script>
    function toggleForm() {
        const tipe = document.getElementById('tipe')?.value;
        const tanggalWrapper = document.getElementById('tanggal-pengembalian-wrapper');
        if (tanggalWrapper) {
            tanggalWrapper.style.display = tipe === 'pinjam' ? 'block' : 'none';
        }
    }

    document.addEventListener('DOMContentLoaded', toggleForm);
</script>
@endsection
