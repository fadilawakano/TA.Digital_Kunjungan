@extends('layouts.perpustakaan') 

@section('content')
<a href="{{ route('perpustakaan.dashboard') }}" 
   class="inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg shadow transition">
    {{-- Ikon Dashboard --}}
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 12l9-9 9 9M4 10v10a1 1 0 001 1h6m8-11v10a1 1 0 01-1 1h-6" />
    </svg>
</a>

<div class="bg-[#BEE4D0] p-6 rounded-3xl shadow-2xl max-w-xl mx-auto mt-10 border border-white/30 backdrop-blur-md">
    <h1 class="text-xl font-semibold mb-4">Profil Saya</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('perpustakaan.profil.update') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Nama</label>
            <input type="text" name="name" class="w-full border border-gray-300 rounded-xl px-3 py-2 bg-white" value="{{ $user->name }}" readonly>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Username</label>
            <input type="text" name="username" class="w-full border border-gray-300 rounded-xl px-3 py-2 bg-white" value="{{ $user->username }}" readonly>
        </div>


        <hr class="my-4">

        <div class="mb-4 relative">
            <label class="block text-sm font-medium text-gray-700">Password Baru</label>
            <input type="password" id="password" name="password" class="w-full border border-gray-300 rounded-xl px-3 py-2 bg-white pr-10" placeholder="Isi password baru" @error('password') border-red-500 @enderror">
            <span onclick="togglePassword('password', this)" class="absolute right-3 top-9 cursor-pointer text-gray-500">
                👁️
            </span>
            @error('password')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4 relative">
            <label class="block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="w-full border border-gray-300 rounded-xl px-3 py-2 bg-white pr-10" placeholder="konfirmasi password anda">
            <span onclick="togglePassword('password_confirmation', this)" class="absolute right-3 top-9 cursor-pointer text-gray-500">
                👁️
            </span>
        </div>

        <button type="submit" class="bg-[#8F87F1] hover:bg-[#BDDDE4] text-white px-5 py-2 rounded-xl shadow-md transition duration-200">Simpan Perubahan</button>
    </form>
</div>

{{-- Script toggle password --}}
<script>
    function togglePassword(fieldId, icon) {
        const field = document.getElementById(fieldId);
        if (field.type === "password") {
            field.type = "text";
            icon.textContent = "🙈";
        } else {
            field.type = "password";
            icon.textContent = "👁️";
        }
    }
</script>
@endsection
