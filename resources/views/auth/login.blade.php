@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex flex-col-reverse lg:flex-row items-center lg:items-center justify-center lg:justify-end relative px-2">

    <!-- Background Image -->
    <img src="{{ asset('img/welcome.png') }}"
         class="absolute inset-0 w-full h-full object-cover object-[center_40%] z-0" />

    <!-- Form -->
    <div class="w-full px-4 flex items-center justify-end min-h-screen relative z-8">
        <div class="w-full max-w-xs sm:max-w-sm lg:max-w-sm bg-white bg-opacity-30 backdrop-blur-md p-6 sm:p-8 rounded-xl shadow-lg ml-auto lg:mr-[240px]">

            <div class="text-center mb-6">
                <div class="flex justify-center mb-2">
                    <img src="{{ asset('img/tutwuri handayani.png') }}" alt="Tut Wuri Handayani"
                        class="h-10 w-10 object-contain">
                </div>
                <h2 class="text-xl font-bold text-gray-800">Welcome again!</h2>
                <p class="text-gray-600 text-sm">Please enter your details</p>
            </div>

            @if (session('success'))
                <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-sm text-center">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm text-center">
                    Username atau password salah.
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Username -->
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input id="username" name="username" type="text" required autofocus
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Password -->
                <div class="mb-4 relative">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input id="password" name="password" type="password" required
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 pr-10">
                    
                    <!-- Eye icon toggle -->
                    <div class="absolute inset-y-0 right-3 top-[35px] flex items-center">
                        <button type="button" onclick="togglePassword()" class="focus:outline-none">
                            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-black text-white py-2 rounded-md hover:bg-gray-800 transition">Log In</button>
                    
            </form>
        </div>
    </div>
</div>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById("password");
        const eyeIcon = document.getElementById("eyeIcon");

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.966 9.966 0 013.84-4.818m4.9-2.161a10.05 10.05 0 012.802.502M15 12a3 3 0 11-6 0 3 3 0 016 0zm6.061 3.061A10.015 10.015 0 0112 19c-4.478 0-8.269-2.943-9.543-7a10.015 10.015 0 012.406-3.475M21 21L3 3" />`;
        } else {
            passwordInput.type = "password";
            eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />`;
        }
    }
</script>
@endsection
