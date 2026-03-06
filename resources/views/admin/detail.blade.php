@extends('layouts.master')

@section('title', 'Detail Laporan')

@section('content')

    <div class="mb-3">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-success btn-sm rounded-pill px-3">
            ← Kembali ke Dashboard
        </a>
    </div>

    <div class="row">
        {{-- Detail Laporan Lingkungan --}}
        <div class="col-md-7">
            <div class="card shadow border-0 rounded-4">
                <div class="card-header bg-success text-white">
                    <i data-lucide="leaf" style="width: 20px; height: 20px;"></i> Detail Laporan Lingkungan
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th>Pelapor</th>
                            <td>: {{ $report->user->name }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal</th>
                            <td>: {{ $report->created_at->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <th>Kategori</th>
                            <td>: {{ $report->category ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Judul</th>
                            <td>: <strong>{{ $report->title }}</strong></td>
                        </tr>
                        <tr>
                            <th>Lokasi</th>
                            <td>: <i data-lucide="map-pin" style="width: 18px; height: 18px;"></i> {{ $report->location ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if ($report->status == '0')
                                    <span class="badge bg-danger">Pending</span>
                                @elseif ($report->status == 'proses')
                                    <span class="badge bg-warning text-dark">Proses</span>
                                @else
                                    <span class="badge bg-success">Selesai</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Deskripsi Masalah</th>
                            <td>: {{ $report->description }}</td>
                        </tr>
                    </table>

                    @if ($report->image)
                        <label class="fw-bold text-muted small"><i data-lucide="camera" style="width: 15px; height: 15px;"></i> Foto Bukti dari Pelapor:</label><br>
                        <img src="{{ asset('storage/' . $report->image) }}" class="img-fluid rounded-3 border mt-1"
                            style="max-height: 250px;" alt="Bukti Laporan">
                    @endif
                </div>
            </div>
        </div>

        {{-- Verifikasi & Tanggapan --}}
        <div class="col-md-5">
            <div class="card shadow border-0 rounded-4">
                <div class="card-header bg-success text-white">
                <i data-lucide="file-check" style="width: 20px; height: 20px;"></i> Verifikasi & Tanggapan
                </div>
                <div class="card-body">
                    {{-- Update Status --}}
                    <form action="{{ route('report.update', $report->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="fw-bold">Ubah Status:</label>
                            <select name="status" class="form-select" onchange="konfirmasiStatus(this)"">
                                <option value="0" {{ $report->status == '0' ? 'selected' : '' }}>
                                    Pending
                                </option>
                                <option value="proses" {{ $report->status == 'proses' ? 'selected' : '' }}>
                                    Proses
                                </option>
                                <option value="selesai" {{ $report->status == 'selesai' ? 'selected' : '' }}>
                                    Selesai
                                </option>
                            </select>
                            <small class="text-muted">* Pilih status untuk mengubah otomatis</small>
                        </div>
                    </form>

                    <hr>

                    {{-- Form Tanggapan --}}
                    <form action="{{ route('response.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="report_id" value="{{ $report->id }}">
                        <div class="mb-3">
                            <label class="fw-bold">Berikan Tanggapan:</label>
                            <textarea name="response_text" class="form-control" rows="4"
                                placeholder="Tuliskan tindakan yang akan/sudah dilakukan..." required></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Bukti Foto Tindakan (Opsional)</label>
                            <input type="file" name="image" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-success w-100 fw-bold">
                            KIRIM TANGGAPAN
                        </button>
                    </form>

                    {{-- Riwayat Percakapan --}}
                    <div class="mt-4">
                        <h6 class="fw-bold"><i data-lucide="message-circle" style="width: 20px; height: 20px;"></i> Riwayat Tanggapan:</h6>
                        @if ($report->responses->count() > 0)
                            @foreach ($report->responses as $response)
                                <div class="alert alert-success p-2 mb-2">
                                    <small class="fw-bold text-dark">
                                        {{ $response->user->name }} (Admin)
                                    </small>
                                    <small class="text-muted float-end">
                                        {{ $response->created_at->diffForHumans() }}
                                    </small>
                                    <p class="mb-0 mt-1 text-dark small">
                                        {{ $response->response_text }}
                                    </p>
                                    @if($response->image)
                                        <img src="{{ asset('storage/' . $response->image) }}" width="200" class="mt-2 rounded">
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <p class="text-center text-muted small">Belum ada tanggapan.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function konfirmasiStatus(selectEl) {
        const label = selectEl.options[selectEl.selectedIndex].text.trim();
        Swal.fire({
            title: 'Ubah Status?',
            text: `Status laporan akan diubah menjadi "${label}"`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Ubah',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                selectEl.closest('form').submit();
            } else {
                // Kembalikan ke nilai semula jika dibatalkan
                selectEl.value = "{{ $report->status }}";
            }
        });
    }
</script>

@endsection