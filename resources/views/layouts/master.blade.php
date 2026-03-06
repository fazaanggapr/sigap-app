<!DOCTYPE html>
<html lang="id">
<head>
    {{-- KONFIGURASI PWA AGAR BISA DI-INSTALL --}}
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#198754">
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js');
            });
        }
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - SIGAP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

{{-- SweetAlert2 --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body style="background-color: #f4faf6;">

    <nav class="navbar navbar-expand navbar-dark bg-success mb-4 shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">
                <i data-lucide="leaf" style="width: 20px; height: 20px;"></i> SIGAP LINGKUNGAN
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    @guest
                        <li class="nav-item"><a class="nav-link text-white" href="{{ route('login') }}">Login</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="{{ route('register') }}">Daftar Akun</a></li>
                    @endguest

                    @auth
                        @if(Auth::user()->role == 'admin')
                            <li class="nav-item"><a class="nav-link text-white fw-bold"
                                href="{{ route('admin.dashboard') }}">Dashboard Admin</a></li>
                        @elseif(Auth::user()->role == 'masyarakat')
                            <li class="nav-item"><a class="nav-link text-white" href="{{ route('user.lapor') }}">Buat Laporan</a></li>
                        @endif

                        <li class="nav-item ms-2">
                            <form action="{{ route('logout') }}" method="POST" id="form-logout">
                                @csrf
                                <button type="button" onclick="konfirmasiLogout()" class="btn btn-outline-light btn-sm mt-1 rounded-pill px-3">Logout</button>
                            </form>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        @yield('content')
    </div>

    {{-- SweetAlert: Tampilkan flash message dari session --}}
<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            confirmButtonColor: '#198754',
            timer: 3000,
            timerProgressBar: true,
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: "{{ session('error') }}",
            confirmButtonColor: '#dc3545',
        });
    @endif

        function konfirmasiLogout() {
        Swal.fire({
            title: 'Yakin ingin keluar?',
            text: 'Kamu harus login lagi untuk mengakses aplikasi.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Logout',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-logout').submit();
            }
        });
    }
</script>

        {{-- Lucide Icons --}}
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>lucide.createIcons();</script>

</body>
</html>