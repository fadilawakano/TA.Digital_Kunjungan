@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">
    <div class="bg-white shadow-xl rounded-lg p-8 w-full max-w-md">
        <div class="mb-6 text-center">
            <h2 class="text-2xl font-bold text-gray-800">🔒 Reset Password</h2>
            <p class="text-sm text-gray-500">Silakan masukkan password baru Anda.</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 text-red-600 text-sm">
                @foreach ($errors->all() as $error)
                    <div>- {{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('password.update', $username) }}" method="POST" class="space-y-5">
            @csrf

            <!-- Password Baru -->
            <div>
                <label for="password" class="block text-gray-700 mb-1">Password Baru</label>
                <div class="relative">
                    <input type="password" name="password" id="password"
                        class="w-full border border-gray-300 rounded px-3 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                    <span onclick="togglePassword('password', this)"
                        class="absolute inset-y-0 right-3 flex items-center cursor-pointer text-gray-500 hover:text-blue-600">
                        👁️
                    </span>
                </div>
            </div>

            <!-- Konfirmasi Password -->
            <div>
                <label for="password_confirmation" class="block text-gray-700 mb-1">Konfirmasi Password</label>
                <div class="relative">
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="w-full border border-gray-300 rounded px-3 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                    <span onclick="togglePassword('password_confirmation', this)"
                        class="absolute inset-y-0 right-3 flex items-center cursor-pointer text-gray-500 hover:text-blue-600">
                        👁️
                    </span>
                </div>
            </div>

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded transition duration-200">
                Simpan Password Baru
            </button>
        </form>
    </div>
</div>

<!-- JS Show/Hide Password -->
<script>
    function togglePassword(id, el) {
        const input = document.getElementById(id);
        if (input.type === "password") {
            input.type = "text";
            el.textContent = '🙈';
        } else {
            input.type = "password";
            el.textContent = '👁️';
        }
    }
</script>
@endsection
