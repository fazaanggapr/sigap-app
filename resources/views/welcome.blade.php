@extends('layouts.master')
@section('title', 'Beranda')
@section('content')
    <div class="container mt-5">
        <div class="p-5 mb-4 bg-white border border-success rounded-4 shadow-sm text-center">
            <div class="container-fluid py-5">

                <div class="mb-3" style="font-size: 4rem;"><i data-lucide="leaf" style="width: 50px; height: 50px; color: #198754;"></i></div>

                <h1 class="display-5 fw-bold text-success mb-3">
                    Sistem Informasi Pelaporan Lingkungan (SIGAP)
                </h1>

                <p class="col-md-8 mx-auto fs-5 text-muted">
                    Laporkan masalah kebersihan dan lingkungan di sekitar rumah Anda —
                    tumpukan sampah, got tersumbat, pencemaran, dan lainnya.
                    Cepat, Aman, dan Transparan.
                </p>

                <div class="mt-4 d-flex justify-content-center flex-wrap gap-2">
                    <a href="{{ route('user.lapor') }}" class="btn btn-success btn-lg px-4 shadow-sm rounded-pill">
                        <i data-lucide="file-pen" style="width: 20px; height: 20px;"></i> Buat Laporan Sekarang
                    </a>
                    @guest
                        <a href="{{ route('register') }}" class="btn btn-outline-success btn-lg px-4 rounded-pill shadow-sm">
                            <i data-lucide="user-plus" style="width: 20px; height: 20px;"></i> Daftar Akun Baru
                        </a>
                    @endguest
                </div>

                <div class="row mt-5 text-start">
                    <div class="col-md-4 mb-3">
                        <div class="p-4 bg-light rounded-4 border h-100">
                            <div style="font-size: 2rem;">🗑️</div>
                            <h6 class="fw-bold mt-2">Tumpukan Sampah</h6>
                            <p class="text-muted small mb-0">Laporkan lokasi penumpukan sampah yang mengganggu kesehatan dan kebersihan lingkungan.</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="p-4 bg-light rounded-4 border h-100">
                            <div style="font-size: 2rem;">🚰</div>
                            <h6 class="fw-bold mt-2">Drainase & Got Mampet</h6>
                            <p class="text-muted small mb-0">Laporkan saluran air yang tersumbat agar segera ditangani sebelum menjadi banjir.</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="p-4 bg-light rounded-4 border h-100">
                            <div style="font-size: 2rem;">🌳</div>
                            <h6 class="fw-bold mt-2">Kerusakan Lingkungan</h6>
                            <p class="text-muted small mb-0">Laporkan pencemaran air, udara, atau kerusakan taman dan fasilitas umum di sekitar Anda.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection