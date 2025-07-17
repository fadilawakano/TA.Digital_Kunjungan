<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kunjungan Guru</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #000;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #eee;
        }
    </style>
</head>
<body>

    <h2>LAPORAN KUNJUNGAN GURU<br>PERPUSTAKAAN SMAN 15</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Tanggal</th>
                <th>Tanggal Pengembalian</th>
                <th>Judul Buku</th>
                <th>Jumlah Buku</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($guru as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->user->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_pengembalian)->format('d-m-Y') }}</td>
                    <td>{{ $item->judul_buku ?? '-' }}</td>
                    <td>{{ $item->jumlah_buku ?? '-' }}</td>
                    <td>{{ $item->keterangan ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">Tidak ada data kunjungan guru.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
