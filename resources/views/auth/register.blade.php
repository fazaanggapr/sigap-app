@extends('layouts.master')

@section('title', 'Daftar Akun Warga')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow border-0 rounded-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i data-lucide="file-pen" style="width: 20px; height: 20px; "></i> Registrasi Warga Baru</h5>
                <small class="opacity-75">Daftarkan diri untuk mulai melaporkan masalah lingkungan</small>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('register.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">NIK</label>
                        <input type="number" name="nik" class="form-control"
                            placeholder="Masukkan NIK (16 digit)" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control"
                            placeholder="Nama sesuai KTP" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Username</label>
                        <input type="text" name="username" class="form-control"
                            placeholder="Buat username unik" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Password</label>
                        <input type="password" name="password" class="form-control"
                            placeholder="Minimal 6 karakter" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">No. Telepon</label>
                        <input type="number" name="telp" class="form-control"
                            placeholder="08..." required>
                    </div>
                    <button type="submit" class="btn btn-success w-100 fw-bold">
                        DAFTAR SEKARANG
                    </button>
                    <div class="text-center mt-3">
                        <small>
                            Sudah punya akun?
                            <a href="{{ route('login') }}" class="text-success fw-bold">Login di sini</a>
                        </small>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection