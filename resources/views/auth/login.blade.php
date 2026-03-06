@extends('layouts.master')

@section('title', 'Login Aplikasi')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-5">

        <div class="text-center mb-4">
            <span style="font-size: 3rem;"><i data-lucide="leaf" style="width: 50px; height: 50px; color: #198754;"></i></span>
            <h4 class="fw-bold text-success mt-2">SIGAP LINGKUNGAN</h4>
            <p class="text-muted small">Sistem Informasi Pelaporan Lingkungan</p>
        </div>

        <div class="card shadow border-0 rounded-4">
            <div class="card-header bg-success text-white text-center">
                <h5 class="mb-0"><i data-lucide="lock" style="width: 20px; height: 20px;"></i> Silakan Masuk</h5>
            </div>
            <div class="card-body p-4">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <form action="{{ route('login.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Username</label>
                        <input type="text" name="username" class="form-control"
                            placeholder="Masukkan username Anda" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Password</label>
                        <input type="password" name="password" class="form-control"
                            placeholder="Masukkan password" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100 fw-bold">
                        LOGIN
                    </button>
                </form>
                <div class="text-center mt-3">
                    <small>
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="text-success fw-bold">Daftar di sini</a>
                    </small>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection