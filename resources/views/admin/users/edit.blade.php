@extends('layouts.admin')

@section('content')
<div class="p-4 max-w-md mx-auto">
    <h1 class="text-2xl font-bold mb-4">Edit User</h1>

    <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-4">
        @csrf @method('PUT')

        <div>
            <label>Username</label>
            <input type="text" name="username" value="{{ $user->username }}" class="w-full border p-2" required>
        </div>

        <div>
            <label>Password (Kosongkan jika tidak diubah)</label>
            <input type="password" name="password" class="w-full border p-2">
        </div>

        <div>
            <label>Role</label>
            <select name="role" id="roleSelect" class="w-full border p-2" required onchange="toggleFields(this.value)">
                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="murid" {{ $user->role == 'murid' ? 'selected' : '' }}>Murid</option>
                <option value="guru" {{ $user->role == 'guru' ? 'selected' : '' }}>Guru</option>
                <option value="perpustakaan" {{ $user->role == 'perpustakaan' ? 'selected' : '' }}>Petugas Perpus</option>
                <option value="biologi" {{ $user->role == 'biologi' ? 'selected' : '' }}>Petugas Lab Biologi</option>
                <option value="fisika" {{ $user->role == 'fisika' ? 'selected' : '' }}>Petugas Lab Fisika</option>
                <option value="kimia" {{ $user->role == 'kimia' ? 'selected' : '' }}>Petugas Lab Kimia</option>
            </select>
        </div>

        <div id="kelasGroup" class="{{ $user->role == 'murid' ? '' : 'hidden' }}">
            <label>Kelas</label>
            <input type="text" name="kelas" value="{{ $user->kelas }}" class="w-full border p-2">
        </div>

        <div id="bidangStudiGroup" class="{{ $user->role == 'guru' ? '' : 'hidden' }}">
            <label>Pilih Bidang Studi</label>
            <select name="bidang_studi[]" class="w-full border p-2" multiple>
                @foreach($bidangStudis as $bidang)
                    <option value="{{ $bidang->id }}" {{ in_array($bidang->id, $selectedBidangStudi) ? 'selected' : '' }}>{{ $bidang->nama }}</option>
                @endforeach
            </select>
            <small class="text-gray-500">Gunakan CTRL atau CMD untuk memilih lebih dari satu.</small>
        </div>

        <button class="bg-green-500 text-white px-4 py-2 rounded">Update</button>
    </form>
</div>

<script>
function toggleFields(role) {
    document.getElementById('kelasGroup').classList.toggle('hidden', role !== 'murid');
    document.getElementById('bidangStudiGroup').classList.toggle('hidden', role !== 'guru');
}
</script>
@endsection
