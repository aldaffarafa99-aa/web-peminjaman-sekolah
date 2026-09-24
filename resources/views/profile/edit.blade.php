@extends('layouts.app')
@section('title', 'Profil')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <div class="text-uppercase small fw-bold text-primary">Pengaturan akun</div>
        <h3 class="mb-1">Profil Saya</h3>
        <p class="text-muted mb-0">Perbarui foto, nama, dan alamat email akunmu.</p>
    </div>
    @if ($user->avatar_path)
        <img src="{{ asset('storage/' . $user->avatar_path) }}" alt="Foto profil" class="profile-avatar profile-avatar-image">
    @else
        <span class="profile-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
    @endif
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h5 class="mb-1">Informasi profil</h5>
                <p class="text-muted small mb-4">Data ini digunakan untuk identitas akunmu.</p>

                @if (session('status') === 'profile-updated')
                    <div class="alert alert-success">Profil berhasil diperbarui.</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label for="avatar" class="form-label">Foto profil</label>
                        <input id="avatar" name="avatar" type="file" class="form-control" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
                        <div class="form-text">Format JPG, PNG, atau WEBP. Maksimal 2 MB.</div>
                        @if ($user->avatar_path)
                            <div class="form-check mt-2">
                                <input id="remove_avatar" name="remove_avatar" value="1" type="checkbox" class="form-check-input">
                                <label for="remove_avatar" class="form-check-label">Hapus foto profil</label>
                            </div>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input id="name" name="name" type="text" class="form-control" value="{{ old('name', $user->name) }}" required autofocus>
                    </div>
                    <div class="mb-4">
                        <label for="email" class="form-label">Email</label>
                        <input id="email" name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check2 me-1"></i>Simpan perubahan</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h5 class="mb-1">Keamanan akun</h5>
                <p class="text-muted small mb-4">Gunakan password baru yang kuat dan tidak mudah ditebak.</p>
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Password saat ini</label>
                        <input id="current_password" name="current_password" type="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password baru</label>
                        <input id="password" name="password" type="password" class="form-control" required>
                    </div>
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">Konfirmasi password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-outline-primary"><i class="bi bi-shield-lock me-1"></i>Ubah password</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .profile-avatar { align-items: center; background: #eeedff; border-radius: 50%; color: #5146e5; display: flex; font-size: 1.3rem; font-weight: 700; height: 52px; justify-content: center; width: 52px; } .profile-avatar-image { object-fit: cover; }
    .profile-avatar + * { margin-left: 1rem; }
</style>
@endsection
