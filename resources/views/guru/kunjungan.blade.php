@extends('layouts.guru')

@section('content')
<a href="{{ route('guru.dashboard') }}"
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

    @if(session('success') || session('error'))
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

    @if ($errors->any())
        <div class="bg-red-100 text-red-800 p-3 rounded mb-4 text-sm">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($kerusakan)
        <div class="bg-red-200 text-red-800 p-3 rounded mb-4 text-sm">
            ⚠️ Anda memiliki laporan kerusakan yang belum dikonfirmasi.
        </div>
    @endif

    <form action="{{ route('guru.kunjungan.store') }}" method="POST">
        @csrf
        <input type="hidden" name="lokasi" value="{{ strtolower($lokasi) }}">

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Nama</label>
            <input type="text" name="nama" class="w-full border border-gray-300 rounded-xl px-3 py-2 bg-gray-100" value="{{ Auth::user()->name }}" readonly>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Tanggal</label>
            <input type="date" name="tanggal" class="w-full border border-gray-300 rounded-xl px-3 py-2 bg-white" value="{{ now()->format('Y-m-d') }}" required>
        </div>

        @if (Str::contains(strtolower($lokasi), 'perpustakaan'))

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Judul Buku</label>
                <input type="text" name="judul_buku" class="w-full border border-gray-300 rounded-xl px-3 py-2 bg-white" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Jumlah Buku</label>
                <input type="number" name="jumlah_buku" class="w-full border border-gray-300 rounded-xl px-3 py-2 bg-white" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Tanggal Pengembalian</label>
                <input type="date" name="tanggal_pengembalian" class="w-full border border-gray-300 rounded-xl px-3 py-2 bg-white" required>
            </div>
        @else
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Pilih Kelas</label>
                <select name="kelas[]" multiple class="w-full border border-gray-300 rounded-xl px-3 py-2 bg-white" required>
                    @foreach ($kelasList as $kelas)
                        <option value="{{ $kelas }}">{{ $kelas }}</option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-1">Tekan Ctrl (Windows) atau Command (Mac) untuk memilih lebih dari satu kelas</p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Mata Pelajaran</label>
                <input type="text" name="mata_pelajaran" class="w-full border border-gray-300 rounded-xl px-3 py-2 bg-white" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Alat</label>
                <input type="text" name="alat" class="w-full border border-gray-300 rounded-xl px-3 py-2 bg-white" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Jumlah Alat</label>
                <input type="number" name="jumlah_alat" class="w-full border border-gray-300 rounded-xl px-3 py-2 bg-white" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Judul Materi</label>
                <input type="text" name="judul_materi" class="w-full border border-gray-300 rounded-xl px-3 py-2 bg-white" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Waktu Mulai</label>
                <input type="datetime-local" name="waktu_mulai" class="w-full border border-gray-300 rounded-xl px-3 py-2 bg-white" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Waktu Selesai</label>
                <input type="datetime-local" name="waktu_selesai" class="w-full border border-gray-300 rounded-xl px-3 py-2 bg-white" required>
            </div>
        @endif

        <div class="flex justify-end mt-6">
            <button type="submit" class="bg-[#8F87F1] hover:bg-[#BDDDE4] text-white px-5 py-2 rounded-xl shadow-md transition duration-200">
                Kirim
            </button>
        </div>
    </form>

<script>
    document.getElementById('form-kunjungan').addEventListener('submit', function(e) {
        const waktuMulai = new Date(document.getElementById('waktu_mulai').value);
        const waktuSelesai = new Date(document.getElementById('waktu_selesai').value);
        const now = new Date();

        if (waktuMulai < now || waktuSelesai < now) {
            e.preventDefault();
            alert("Tanggal dan waktu kunjungan sudah lewat. Silakan pilih waktu di masa depan.");
            return;
        }

        if (waktuSelesai <= waktuMulai) {
            e.preventDefault();
            alert("Waktu selesai harus lebih dari waktu mulai.");
        }
    });
</script>

</div>
@endsection
