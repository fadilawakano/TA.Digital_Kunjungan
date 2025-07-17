<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - Buku Kunjungan</title>
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen bg-cover bg-center bg-no-repeat flex items-center justify-center" style="background-image: url('{{ asset('img/welcome.png') }}')">

    <div class="w-full h-full flex items-center justify-end pr-6 sm:pr-10 md:pr-20 lg:pr-[240px]">

        <div class="bg-white bg-opacity-70 backdrop-blur-md rounded-xl shadow-lg text-center 
                    p-4 sm:p-6 w-full max-w-xs sm:max-w-sm lg:w-[360px]">

            <!-- Logo -->
            <div class="flex justify-center mb-4">
                <img src="{{ asset('img/tutwuri handayani.png') }}" alt="Tut Wuri Handayani" class="h-10 w-10 object-contain">
            </div>

            <!-- Judul -->
            <h1 class="text-xl sm:text-2xl font-bold text-gray-800 mb-4 leading-snug">
                BUKU KUNJUNGAN DIGITAL<br><span class="text-base">-</span><br>SMAN 15 SBB
            </h1>

            <!-- Tombol -->
            <a href="{{ route('login') }}" class="bg-emerald-700 hover:bg-emerald-800 text-white font-bold py-2 px-6 rounded-full shadow-md transition">
                LOGIN
            </a>
        </div>

    </div>

</body>
</html>
