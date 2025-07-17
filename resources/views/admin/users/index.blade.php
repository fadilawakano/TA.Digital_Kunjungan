@extends('layouts.admin')

@section('content')
<a href="{{ route('admin.dashboard') }}" 
   class="inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg shadow transition">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 12l9-9 9 9M4 10v10a1 1 0 001 1h6m8-11v10a1 1 0 01-1 1h-6" />
    </svg>
</a>
<div class="p-4">
    <h1 class="text-2xl font-bold mb-4">Manajemen Pengguna</h1>

    <a href="{{ route('admin.users.create') }}"
   class="inline-flex items-center gap-2 bg-[#536493] hover:bg-[#6c92ad] text-white font-semibold px-4 py-2 rounded-lg shadow transition">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
    </svg>
    Tambah User
</a>

<!-- Form Pencarian -->
    <form method="GET" action="{{ route('admin.users.index') }}" class="flex items-center">
        <input type="text" name="search" placeholder="Cari pengguna..." value="{{ request('search') }}"
               class="px-3 py-1.5 border border-gray-300 rounded-l-lg focus:outline-none focus:ring focus:border-blue-300 text-sm">
        <button type="submit"
                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-3 py-1.5 rounded-r-lg text-sm">
            Cari
        </button>
    </form>
</div>


    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-2 mt-4 rounded">{{ session('success') }}</div>
    @endif

    <table class="w-full mt-4 border">
        <thead>
            <tr class="bg-gray-200 text-left">
                <th class="p-2">ID</th>
                <th class="p-2">Username</th>
                <th class="p-2">Role</th>
                <th class="p-2">Kelas</th>
                <th class="p-2">Bidang Studi</th>
                <th class="p-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr class="border-t">
                <td class="p-2">{{ $users->firstItem() + $loop->index }}</td>
                <td class="p-2">{{ $user->username }}</td>
                <td class="p-2">{{ $user->role }}</td>
                <td class="p-2">
                    @if($user->role == 'murid')
                        {{ $user->kelas ?? '-' }}
                    @else
                        -
                    @endif
                </td>
                <td class="p-2">
                    @if($user->role == 'guru')
                        {{ $user->bidangStudis->pluck('nama')->join(', ') }}
                    @else
                        -
                    @endif
                </td>
                <td class="p-2 flex items-center gap-2">
    <a href="{{ route('admin.users.edit', $user) }}" 
       class="inline-flex items-center gap-1 bg-blue-100 hover:bg-blue-200 text-blue-700 text-sm font-semibold px-3 py-1.5 rounded-full transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M11 5h2m-1 1v2m0 4v6m-4-6h8a2 2 0 002-2V7a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z" />
        </svg>
        Edit
    </a>

    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin hapus?')">
        @csrf @method('DELETE')
        <button type="submit" 
                class="inline-flex items-center gap-1 bg-red-100 hover:bg-red-200 text-red-600 text-sm font-semibold px-3 py-1.5 rounded-full transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M6 18L18 6M6 6l12 12"/>
            </svg>
            Hapus
        </button>
    </form>
</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if ($users->hasPages())
    <div class="mt-6 flex flex-wrap justify-center gap-2 text-sm">
        {{-- Tombol Sebelumnya --}}
        @if ($users->onFirstPage())
            <span class="px-3 py-1 bg-gray-300 text-gray-500 rounded cursor-not-allowed">←</span>
        @else
            <a href="{{ $users->previousPageUrl() }}" class="px-3 py-1 bg-[#C7C8CC] text-black rounded hover:bg-[#b0b0b0]">←</a>
        @endif

        {{-- Nomor Halaman --}}
        @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
            @if ($page == $users->currentPage())
                <span class="px-3 py-1 bg-[#7EACB5] text-white rounded font-semibold">{{ $page }}</span>
            @else
                <a href="{{ $url }}" class="px-3 py-1 bg-[#C7C8CC] text-black rounded hover:bg-[#b0b0b0]">{{ $page }}</a>
            @endif
        @endforeach

        @if($users->isEmpty())
<tr>
    <td colspan="6" class="text-center text-gray-500 p-4">Tidak ada pengguna ditemukan.</td>
</tr>
@endif

        {{-- Tombol Selanjutnya --}}
        @if ($users->hasMorePages())
            <a href="{{ $users->nextPageUrl() }}" class="px-3 py-1 bg-[#C7C8CC] text-black rounded hover:bg-[#b0b0b0]">→</a>
        @else
            <span class="px-3 py-1 bg-gray-300 text-gray-500 rounded cursor-not-allowed">→</span>
        @endif
    </div>
@endif

@endsection
