@extends('layouts.master')

@section('title', 'Tulis Pengaduan')

@section('content')
<div class="row">

    {{-- ALERT WELCOME --}}
    <div class="alert alert-info" role="alert">
        <marquee direction="left" scrollamount="8">
            Selamat datang, <strong>{{ Auth::user()->name }}</strong>!
            Silakan laporkan keluhan dengan jujur dan sopan. Admin siaga 24 jam.
        </marquee>
    </div>

    {{-- KOLOM KIRI: FORM --}}
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
                        <input type="text" name="title" class="form-control"
                               placeholder="Contoh: Jalan Berlubang" required>
                    </div>

                    {{-- DESKRIPSI --}}
                    <div class="mb-3">
                        <label class="form-label">Isi Keluhan</label>
                        <textarea name="description" class="form-control" rows="4" required></textarea>
                    </div>

                    {{-- LOKASI --}}
                    <div class="mb-3">
                        <label class="form-label">Lokasi Kejadian</label>

                        <input type="text" name="location" id="location_text"
                               class="form-control mb-2"
                               placeholder="Geser marker di peta..." required>

                        <div id="map" style="height:300px;border-radius:10px;border:1px solid #ccc;"></div>

                        <input type="hidden" name="latitude" id="latitude">
                        <input type="hidden" name="longitude" id="longitude">
                    </div>

                    {{-- FOTO --}}
                    <div class="mb-3">
                        <label class="form-label">Bukti Foto</label>
                        <input type="file" name="image" class="form-control">
                        <small class="text-muted">Format JPG/PNG max 2MB</small>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        KIRIM LAPORAN
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- KOLOM KANAN: RIWAYAT --}}
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
                            <th>Status & Balasan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reports as $item)
                        <tr>
                            <td>
                                <strong>{{ $item->title }}</strong><br>
                                <small class="text-muted">
                                    {{ $item->created_at->format('d/m/Y H:i') }}
                                </small>

                                @if ($item->image)
                                    <br>
                                    <img src="{{ asset('storage/' . $item->image) }}"
                                         width="80" class="mt-2 rounded">
                                @endif
                            </td>

                            <td>
                                {{-- STATUS --}}
                                @if ($item->status == '0')
                                    <span class="badge bg-danger">Menunggu</span>
                                @elseif ($item->status == 'proses')
                                    <span class="badge bg-warning">Diproses</span>
                                @else
                                    <span class="badge bg-success">Selesai</span>
                                @endif

                                {{-- BALASAN --}}
                                @if ($item->responses->count() > 0)
                                    @php $last = $item->responses->last(); @endphp

                                    <div class="mt-2 p-2 border rounded bg-light">
                                        <small>
                                            <strong>Admin:</strong>
                                            {{ $last->response_text }}
                                        </small>

                                        @if ($last->image)
                                            <br>
                                            <img src="{{ asset('storage/' . $last->image) }}"
                                                 width="100" class="mt-1 rounded border">
                                        @endif
                                    </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
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

    marker.on('dragend', () => {
        const c = marker.getLatLng();
        latitude.value = c.lat;
        longitude.value = c.lng;
        getAddress(c.lat, c.lng);
    });

    map.on('click', (e) => {
        marker.setLatLng(e.latlng);
        latitude.value = e.latlng.lat;
        longitude.value = e.latlng.lng;
        getAddress(e.latlng.lat, e.latlng.lng);
    });
</script>
@endsection
