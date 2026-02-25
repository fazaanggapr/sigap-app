@extends('layouts.master')

@section('title', 'Tulis Pengaduan')

@section('content')
    <div class="row">

        {{-- ALERT WELCOME --}}
        <div class="alert alert-info" role="alert">
            <marquee direction="left" scrollamount="8">
                Selamat datang, <strong>{{ Auth::user()->name }}</strong>!
                Silakan laporkan keluhan dengan jujur dan sopan.
            </marquee>
        </div>

        {{-- FORM LAPORAN --}}
        <div class="col-md-5">

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    Tulis Laporan Baru
                </div>

                <div class="card-body">
                    <form action="{{ route('user.lapor.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- JUDUL --}}
                        <div class="mb-3">
                            <label class="form-label">Judul Laporan</label>
                            <input type="text" name="title" class="form-control" placeholder="Contoh: Jalan Berlubang"
                                required>
                        </div>

                        {{-- DESKRIPSI --}}
                        <div class="mb-3">
                            <label class="form-label">Isi Keluhan</label>
                            <textarea name="description" class="form-control" rows="4" required></textarea>
                        </div>

                        {{-- LOKASI --}}
                        <div class="mb-3">
                            <label class="form-label">Lokasi Kejadian</label>

                            <input type="text" name="location" id="location_text" class="form-control mb-2"
                                placeholder="Geser marker di peta...">

                            <div id="map" style="height: 300px; border-radius: 10px; border: 1px solid #ccc;">
                            </div>

                            <input type="hidden" name="latitude" id="latitude">
                            <input type="hidden" name="longitude" id="longitude">
                        </div>

                        {{-- FOTO --}}
                        <div class="mb-3">
                            <label class="form-label">Bukti Foto</label>
                            <input type="file" name="image" class="form-control">
                            <small class="text-muted">
                                Format JPG/PNG max 2MB
                            </small>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            KIRIM LAPORAN
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- RIWAYAT LAPORAN --}}
        <div class="col-md-7">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    Riwayat Laporan Saya
                </div>

                <div class="card-body">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Tanggal</th>
                                <th>Judul</th>
                                <th>Status & Balasan</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($reports as $item)
                                <tr>

                                    {{-- DATA --}}
                                    <td style="min-width: 220px;">
                                        <strong>{{ $item->title }}</strong><br>
                                        <small class="text-muted">
                                            {{ $item->created_at->format('d/m/Y H:i') }}
                                        </small>

                                        @if ($item->image)
                                            <br>
                                            <img src="{{ asset('storage/' . $item->image) }}" width="80" class="mt-2 rounded">
                                        @endif
                                    </td>

                                    {{-- TANGGAL --}}
                                    <td>
                                        {{ $item->created_at->format('d/m/Y') }}
                                    </td>

                                    {{-- DESKRIPSI SINGKAT --}}
                                    <td>
                                        <strong>{{ $item->title }}</strong><br>
                                        <small class="text-muted">
                                            {{ Str::limit($item->description, 30) }}
                                        </small>
                                    </td>

                                    {{-- STATUS & TIMELINE --}}
                                    <td style="min-width: 300px;">

                                        {{-- STATUS BADGE --}}
                                        <div class="mb-3">
                                            @if ($item->status == '0')
                                                <span class="badge bg-danger px-3 py-2 rounded-pill">
                                                    Menunggu
                                                </span>
                                            @elseif ($item->status == 'proses')
                                                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">
                                                    Sedang Diproses
                                                </span>
                                            @else
                                                <span class="badge bg-success px-3 py-2 rounded-pill">
                                                    Selesai
                                                </span>
                                            @endif
                                        </div>

                                        {{-- TIMELINE --}}
                                        @if ($item->responses->count() > 0)

                                            <div class="ps-3 mt-2" style="border-left: 3px solid #dee2e6;">

                                                @foreach ($item->responses as $resp)
                                                    <div class="position-relative mb-3">

                                                        {{-- BULLET --}}
                                                        <span class="position-absolute bg-primary rounded-circle" style="
                                                                              width: 12px;
                                                                              height: 12px;
                                                                              left: -26px;
                                                                              top: 6px;
                                                                              border: 2px solid white;
                                                                          ">
                                                        </span>

                                                        {{-- BOX --}}
                                                        <div class="bg-light p-3 rounded-3 border shadow-sm">
                                                            <small class="text-primary fw-bold d-block mb-1">
                                                                {{ $resp->created_at->format('d M Y, H:i') }}
                                                            </small>

                                                            <p class="mb-2 text-dark small">
                                                                <strong>Petugas:</strong>
                                                                {{ $resp->response_text }}
                                                            </p>

                                                            @if ($resp->image)
                                                                <img src="{{ asset('storage/' . $resp->image) }}"
                                                                    class="img-fluid rounded border" style="max-height: 100px;">
                                                            @endif
                                                        </div>

                                                    </div>
                                                @endforeach

                                            </div>

                                        @else
                                            <p class="text-muted small mt-2">
                                                <em>Belum ada tindakan dari petugas.</em>
                                            </p>
                                        @endif

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        Belum ada laporan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    {{-- LEAFLET MAP --}}
    <script>
        const map = L.map('map').setView([-6.2, 106.816666], 13);

        L.tileLayer(
            'https://tile.openstreetmap.org/{z}/{x}/{y}.png',
            {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }
        ).addTo(map);

        const marker = L.marker(
            [-6.2, 106.816666],
            { draggable: true }
        ).addTo(map);

        function getAddress(lat, lng) {
            document.getElementById("location_text").value = "Mencari lokasi...";

            fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById("location_text").value =
                        data.display_name || "";
                })
                .catch(() => {
                    document.getElementById("location_text").value =
                        "Alamat tidak ditemukan (isi manual)";
                });
        }

        // ==================================================================== 
        // FITUR AUTO-DETECT LOKASI PENGGUNA (GPS BROWSER) 
        // ==================================================================== 
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                // 1. Ambil koordinat GPS asli dari perangkat 
                var lat = position.coords.latitude;
                var lng = position.coords.longitude;
                // 2. Terbangkan peta ke lokasi asli pengguna 
                map.setView([lat, lng], 16);
                marker.setLatLng([lat, lng]);
                // 3. Simpan koordinat ke form rahasia untuk database 
                document.getElementById("latitude").value = lat;
                document.getElementById("longitude").value = lng;
                // 4. Minta Leaflet menerjemahkan jalan 
                getAddress(lat, lng);
            }, function () {
                alert("Akses lokasi ditolak. Peta tetap berada di lokasi default (Jakarta).");
            });
        }

        marker.on('dragend', () => {
            const c = marker.getLatLng();
            document.getElementById('latitude').value = c.lat;
            document.getElementById('longitude').value = c.lng;
            getAddress(c.lat, c.lng);
        });

        map.on('click', (e) => {
            marker.setLatLng(e.latlng);
            document.getElementById('latitude').value = e.latlng.lat;
            document.getElementById('longitude').value = e.latlng.lng;
            getAddress(e.latlng.lat, e.latlng.lng);
        });
    </script>
@endsection