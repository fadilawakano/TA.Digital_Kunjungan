<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow sm:rounded-lg p-6">

                @if (session('status'))
                    <div class="mb-4 text-green-600 font-semibold">
                        {{ session('status') }}
                    </div>
                @endif

                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <!-- Nama -->
                    <div class="mb-4">
                        <label for="name" class="block font-medium text-sm text-gray-700">Nama</label>
                        <input type="text" name="name" id="name"
                            value="{{ old('name', auth()->user()->name) }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200">
                        @error('name')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <label for="password" class="block font-medium text-sm text-gray-700">Password Baru (opsional)</label>
                        <input type="password" name="password" id="password"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200">
                        @error('password')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror

                        <input type="password" name="password_confirmation" id="password_confirmation"
                            placeholder="Konfirmasi Password"
                            class="mt-2 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200">
                    </div>

                    <!-- Submit -->
                    <div class="flex justify-end">
                        <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded hover:bg-indigo-700">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Optional: Form hapus akun -->
            {{-- @include('profile.partials.delete-user-form') --}}
        </div>
    </div>
</x-app-layout>
