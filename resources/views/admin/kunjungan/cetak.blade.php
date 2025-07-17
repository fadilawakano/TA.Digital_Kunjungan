<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kunjungan</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 8px; text-align: center; }
        h2, h3 { text-align: center; }
    </style>
</head>
<body>
    <h2>Laporan Kunjungan {{ ucfirst($tab) }}</h2>

    @if($lokasi === 'perpustakaan')
        {{-- TABEL BACA --}}
        @php $kunjunganBaca = $kunjungan->where('tipe', 'baca'); @endphp
        @if($kunjunganBaca->count())
            <h3>Buku Kunjungan Perpustakaan</h3>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        @if($tab === 'murid')
                            <th>Kelas</th>
                        @endif
                        <th>Judul Buku</th>
                        <th>Jumlah Buku</th>
                        <th>Tanggal</th>
                        <th>Tipe</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kunjunganBaca as $index => $k)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $k->user->name }}</td>
                            @if($tab === 'murid')
                                <td>{{ $k->kelas }}</td>
                            @endif
                            <td>{{ $k->judul_buku }}</td>
                            <td>{{ $k->jumlah_buku }}</td>
                            <td>{{ $k->tanggal }}</td>
                            <td>Baca</td>
                            <td>{{ $k->verifikasi_petugas ? 'Terverifikasi' : 'Menunggu' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        {{-- TABEL PINJAM --}}
        @php $kunjunganPinjam = $kunjungan->where('tipe', 'pinjam'); @endphp
        @if($kunjunganPinjam->count())
            <h3 style="margin-top: 40px;">Buku Peminjaman Perpustakaan</h3>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        @if($tab === 'murid')
                            <th>Kelas</th>
                        @endif
                        <th>Judul Buku</th>
                        <th>Jumlah Buku</th>
                        <th>Tanggal</th>
                        <th>Tipe</th>
                        <th>Tanggal Pengembalian</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kunjunganPinjam as $index => $k)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $k->user->name }}</td>
                            @if($tab === 'murid')
                                <td>{{ $k->kelas }}</td>
                            @endif
                            <td>{{ $k->judul_buku }}</td>
                            <td>{{ $k->jumlah_buku }}</td>
                            <td>{{ $k->tanggal }}</td>
                            <td>Pinjam</td>
                            <td>{{ $k->tanggal_pengembalian }}</td>
                            <td>{{ $k->verifikasi_petugas ? 'Terverifikasi' : 'Menunggu' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        @if($kunjunganBaca->count() === 0 && $kunjunganPinjam->count() === 0)
            <p style="text-align:center; margin-top: 40px;">Tidak ada data kunjungan perpustakaan.</p>
        @endif

    @else
        {{-- TABEL LAB --}}
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    @if($tab === 'murid')
                        <th>Kelas</th>
                    @endif
                    <th>Mata Pelajaran</th>
                    <th>Judul Materi</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kunjungan as $index => $k)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $k->user->name }}</td>
                        @if($tab === 'murid')
                            <td>{{ $k->kelas }}</td>
                        @endif
                        <td>{{ $k->mata_pelajaran }}</td>
                        <td>{{ $k->judul_materi }}</td>
                        <td>{{ $k->tanggal }}</td>
                        <td>{{ $k->verifikasi_petugas ? 'Terverifikasi' : 'Menunggu' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7">Tidak ada data kunjungan laboratorium.</td></tr>
                @endforelse
            </tbody>
        </table>
    @endif
</body>
</html>
