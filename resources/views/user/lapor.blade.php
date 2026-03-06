@extends('layouts.master')

@section('title', 'Lapor Masalah Lingkungan')

@section('content')

    <div class="row">
        {{-- ALERT WELCOME --}}
        <div class="alert alert-success" role="alert">
            <marquee direction="left" scrollamount="8">
                <i data-lucide="leaf" style="width: 20px; height: 20px; color: #198754;"></i> Selamat datang,
                <strong>{{ Auth::user()->name }}</strong>!
                Silakan laporkan masalah kebersihan atau lingkungan di sekitar Anda. Laporan Anda sangat berarti untuk
                lingkungan yang lebih bersih! <i data-lucide="leaf" style="width: 20px; height: 20px; color: #198754;"></i>
            </marquee>
        </div>

        {{-- FORM LAPORAN --}}
        <div class="col-md-5">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="card shadow border-0 rounded-4">
                <div class="card-header bg-success text-white">
                    <i data-lucide="leaf" style="width: 20px; height: 20px;"></i> Buat Laporan Lingkungan Baru
                </div>
                <div class="card-body">
                    <form action="{{ route('user.lapor.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- KATEGORI MASALAH --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Kategori Masalah</label>
                            <select name="category" class="form-select">
                                <option value="">-- Pilih Kategori --</option>
                                <option value="Tumpukan Sampah">🗑️ Tumpukan Sampah</option>
                                <option value="Got / Drainase Tersumbat">🚰 Got / Drainase Tersumbat</option>
                                <option value="Pencemaran Air">💧 Pencemaran Air</option>
                                <option value="Pencemaran Udara">🌫️ Pencemaran Udara</option>
                                <option value="Kerusakan Taman / Pohon">🌳 Kerusakan Taman / Pohon</option>
                                <option value="Sarang Hama">🐀 Sarang Hama (Tikus/Nyamuk)</option>
                                <option value="Lainnya">⚠️ Lainnya</option>
                            </select>
                            <small class="text-muted">Pilih kategori yang paling sesuai</small>
                        </div>

                        {{-- JUDUL --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Judul Laporan</label>
                            <input type="text" name="title" class="form-control"
                                placeholder="Contoh: Tumpukan Sampah di Depan RT 05" required>
                        </div>

                        {{-- DESKRIPSI --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Deskripsi Masalah</label>
                            <textarea name="description" class="form-control" rows="4"
                                placeholder="Jelaskan masalah lingkungan yang Anda temui secara detail (kondisi, dampak, dll)..."
                                required></textarea>
                        </div>

                        {{-- LOKASI --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold"><i data-lucide="map-pin"
                                    style="width: 20px; height: 20px;"></i> Lokasi Kejadian</label>
                            <input type="text" name="location" id="location_text" class="form-control mb-2"
                                placeholder="Geser marker di peta untuk mengisi otomatis...">
                            <div id="map" style="height: 300px; border-radius: 10px; border: 2px solid #198754;">
                            </div>
                            <input type="hidden" name="latitude" id="latitude">
                            <input type="hidden" name="longitude" id="longitude">
                            <small class="text-muted">Tandai titik lokasi persis masalah lingkungan di peta</small>
                        </div>

                        {{-- FOTO --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold"><i data-lucide="camera"
                                    style="width: 20px; height: 20px;"></i> Foto Bukti</label>
                            <input type="file" name="image" class="form-control">
                            <small class="text-muted">Foto kondisi masalah lingkungan (JPG/PNG, maks. 2MB)</small>
                        </div>

                        <button type="submit" class="btn btn-success w-100 fw-bold">
                            KIRIM LAPORAN
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- RIWAYAT LAPORAN --}}
        <div class="col-md-7">
            <div class="card shadow border-0 rounded-4">
                <div class="card-header bg-success text-white">
                    <i data-lucide="file-text" style="width: 20px; height: 20px;"></i> Riwayat Laporan Saya
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
                                    <td style="min-width: 220px;">
                                        <strong>{{ $item->title }}</strong><br>
                                        <small class="text-muted">{{ $item->created_at->format('d/m/Y H:i') }}</small>
                                        @if ($item->image)
                                            <br>
                                            <img src="{{ asset('storage/' . $item->image) }}" width="80" class="mt-2 rounded">
                                        @endif
                                    </td>
                                    <td>{{ $item->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <strong>{{ $item->title }}</strong><br>
                                        <small class="text-muted">{{ Str::limit($item->description, 30) }}</small>
                                    </td>
                                    <td style="min-width: 300px;">
                                        <div class="mb-3">
                                            @if ($item->status == '0')
                                                <span class="badge bg-danger px-3 py-2 rounded-pill">Menunggu</span>
                                            @elseif ($item->status == 'proses')
                                                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">Sedang
                                                    Diproses</span>
                                            @else
                                                <span class="badge bg-success px-3 py-2 rounded-pill">Selesai</span>
                                            @endif
                                        </div>
                                        @if ($item->responses->count() > 0)
                                            <div class="ps-3 mt-2" style="border-left: 3px solid #198754;">
                                                @foreach ($item->responses as $resp)
                                                    <div class="position-relative mb-3">
                                                        <span class="position-absolute bg-success rounded-circle" style="
                                                                    width:12px;
                                                                    height:12px;
                                                                    left:-22px;
                                                                    top:50%;
                                                                    transform: translateY(-50%);
                                                                    border:2px solid white;">
                                                        </span>
                                                        <div class="bg-light p-3 rounded-3 border shadow-sm">
                                                            <small class="text-success fw-bold d-block mb-1">
                                                                {{ $resp->created_at->format('d M Y, H:i') }}
                                                            </small>
                                                            <p class="mb-2 text-dark small">
                                                                <strong>Petugas:</strong> {{ $resp->response_text }}
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
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <i data-lucide="leaf" style="width: 35px; height: 35px; color: #198754;"></i> Belum ada
                                        laporan. Ayo mulai laporkan masalah lingkungan di sekitar Anda!
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
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        const marker = L.marker([-6.2, 106.816666], { draggable: true }).addTo(map);

        function getAddress(lat, lng) {
            document.getElementById("location_text").value = "Mencari lokasi...";
            fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById("location_text").value = data.display_name || "";
                })
                .catch(() => {
                    document.getElementById("location_text").value = "Alamat tidak ditemukan (isi manual)";
                });
        }

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                var lat = position.coords.latitude;
                var lng = position.coords.longitude;
                map.setView([lat, lng], 16);
                marker.setLatLng([lat, lng]);
                document.getElementById("latitude").value = lat;
                document.getElementById("longitude").value = lng;
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