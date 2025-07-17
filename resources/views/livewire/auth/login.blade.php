<x-layouts.guest>
    <div class="backdrop-blur-sm bg-white/20 border border-white/30 rounded-xl shadow-xl p-8 w-full max-w-md text-white">
        <h2 class="text-2xl font-bold text-center mb-6">Login</h2>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Role --}}
            <select id="role-select" name="role" required class="mb-4 w-full rounded bg-white/20 text-black px-4 py-2 focus:outline-none focus:ring">
                <option value="" disabled selected>Pilih Role</option>
                <option value="murid">Murid</option>
                <option value="guru">Guru</option>
                <option value="lab_biologi">Petugas Lab Biologi</option>
                <option value="lab_fisika">Petugas Lab Fisika</option>
                <option value="lab_kimia">Petugas Lab Kimia</option>
                <option value="perpus">Petugas Perpustakaan</option>
            </select>

            {{-- Email --}}
            <div class="mb-4">
                <label class="block mb-1">Email</label>
                <input type="email" name="email" class="w-full px-4 py-2 rounded bg-white/20 text-white focus:outline-none focus:ring" required>
            </div>

            {{-- Password --}}
            <div class="mb-4">
                <label class="block mb-1">Password</label>
                <input type="password" name="password" class="w-full px-4 py-2 rounded bg-white/20 text-white focus:outline-none focus:ring" required>
            </div>

            {{-- NIP (hanya untuk guru & petugas) --}}
            <div class="mb-4" id="nip-field" style="display: none;">
                <label class="block mb-1">NIP</label>
                <input type="text" name="nip" class="w-full px-4 py-2 rounded bg-white/20 text-white focus:outline-none focus:ring">
            </div>

            {{-- Remember me & forgot password --}}
            <div class="flex items-center justify-between mb-4 text-sm">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="mr-2">
                    Remember me
                </label>
                <a href="#" class="hover:underline">Forgot Password?</a>
            </div>

            {{-- Submit --}}
            <button type="submit" class="w-full py-2 px-4 rounded bg-white/30 hover:bg-white/50 text-white font-semibold">Login</button>
        </form>

        {{-- Register link --}}
        <div class="text-center mt-4 text-sm">
            Don't have an account?
            <a href="#" class="underline">Register</a>
        </div>
    </div>

    {{-- Script toggle NIP --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const roleSelect = document.getElementById('role-select');
            const nipField = document.getElementById('nip-field');

            roleSelect.addEventListener('change', function () {
                const selected = this.value;

                // Role yang wajib isi NIP
                const rolesWithNip = ['guru', 'lab_biologi', 'lab_fisika', 'lab_kimia', 'perpus'];

                if (rolesWithNip.includes(selected)) {
                    nipField.style.display = 'block';
                } else {
                    nipField.style.display = 'none';
                }
            });
        });
    </script>
</x-layouts.guest>
