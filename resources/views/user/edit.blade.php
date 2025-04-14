@extends('layouts.template')

@section('content')
<div class="container mt-5">
    <h2>Edit User</h2>

    <form action="{{ route('user.update',$user['id']) }}" method="POST">
        @method('PATCH')
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Alamat Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Kata Sandi (Kosongkan jika tidak diubah)</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Kata sandi baru">
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Peran (Role)</label>
            <select class="form-select" id="role" name="role" required>
                <option value="Admin" {{ $user->role == "Admin" ? 'selected' : '' }}>Admin</option>
                <option value="Employee" {{ $user->role == "Employee" ? 'selected' : '' }}>Employee</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>
</div>
@endsection
