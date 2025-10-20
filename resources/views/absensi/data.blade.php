@extends('layouts.app')

@section('title', 'Data Absensi - PT. Vortex Energy Batam')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Data Absensi</h1>
    </div>

    <div class="mt-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <div class="card shadow border-0">
        <div class="card-body">

            {{-- Filter Section --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <form id="filterForm" action="{{ route('absensi.data') }}" method="GET">
                        <div class="row align-items-end">
                            <div class="col-md-3">
                                <label for="bulan">Bulan</label>
                                <select class="form-control" id="bulan" name="bulan">
                                    @foreach (['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $namaBulan)
                                        {{-- value diubah menjadi $loop->iteration (1, 2, 3, ...) --}}
                                        <option value="{{ $loop->iteration }}" {{-- Logika 'selected' diubah untuk membandingkan angka (date('n')) --}}
                                            {{ request('bulan', date('n')) == $loop->iteration ? 'selected' : '' }}>

                                            {{ $namaBulan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label for="tahun">Tahun</label>
                                <input type="number" class="form-control" id="tahun" name="tahun"
                                    value="{{ request('tahun', date('Y')) }}" min="2020" max="2099">
                            </div>

                            <div class="col-md-3">
                                <label for="status">Status</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="">Semua Status</option>
                                    <option value="hadir" {{ request('status') == 'hadir' ? 'selected' : '' }}>Hadir
                                    </option>
                                    <option value="terlambat" {{ request('status') == 'terlambat' ? 'selected' : '' }}>
                                        Terlambat</option>
                                    <option value="tidak_hadir" {{ request('status') == 'tidak_hadir' ? 'selected' : '' }}>
                                        Tidak Hadir</option>
                                </select>
                            </div>

                        </div>

                        <div class="mt-3 d-flex justify-content-end align-items-center">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-filter me-1"></i> Terapkan
                            </button>
                            <a href="{{ route('absensi.data') }}" class="btn btn-secondary me-2">
                                <i class="fas fa-sync-alt me-1"></i> Reset
                            </a>

                            {{-- Import --}}
                            <button type="button" class="btn btn-success me-2" data-bs-toggle="modal"
                                data-bs-target="#importModal">
                                <i class="fas fa-file-import me-1"></i> Import
                            </button>
                            {{-- Export --}}
                            <a href="{{ route('absensi.data.exportAll') }}" class="btn btn-info">
                                <i class="fas fa-file-export me-1"></i> Export
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center" id="absensiTable">
                    <thead class="bg-light text-dark fw-semibold">
                        <tr>
                            <th>No</th>
                            <th>Departemen</th>
                            <th>Nama</th>
                            <th>Badge</th>
                            <th>Tanggal</th>
                            <th>Waktu In | Out</th>
                            <th>Total Jam</th>
                            <th>Kode Verifikasi</th>
                            <th>Lokasi</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($absensis as $key => $records)
                            @php
                                $first = $records->first();
                                $masuk = $records->firstWhere('tipe_absen', 'masuk');
                                $pulang = $records->firstWhere('tipe_absen', 'pulang');
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $first->user->departement ?? '-' }}</td>
                                <td>{{ $first->user->name ?? '-' }}</td>
                                <td>{{ $first->user->badge_number ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($first->tanggal_waktu)->format('d/m/Y') }}</td>

                                {{-- Kolom Waktu In | Out --}}
                                <td>
                                    <div class="d-flex flex-column">
                                        {{ $masuk ? date('H:i', strtotime($masuk->tanggal_waktu)) : '-' }}
                                        |
                                        {{ $pulang ? date('H:i', strtotime($pulang->tanggal_waktu)) : '-' }}
                                    </div>
                                </td>
                                <td>
                                    @if ($masuk && $pulang)
                                        {{ gmdate('H:i:s', strtotime($pulang->tanggal_waktu) - strtotime($masuk->tanggal_waktu)) }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-column align-items-center gap-1">

                                        {{-- Logika untuk Absen Masuk --}}
                                        @if ($masuk?->foto)
                                            {{-- Cek apakah datanya adalah path (mengandung '/') --}}
                                            @if (Str::contains($masuk->foto, '/'))
                                                <img src="{{ asset('storage/' . $masuk->foto) }}" width="40"
                                                    height="40" class="rounded shadow-sm" alt="Foto Masuk">
                                            @else
                                                <span class="badge bg-white"><i class="fas fa-info-circle me-1"></i>
                                                    {{ $masuk->foto }}</span>
                                            @endif
                                        @endif

                                        {{-- Logika untuk Absen Pulang --}}
                                        @if ($pulang?->foto)
                                            @if (Str::contains($pulang->foto, '/'))
                                                <img src="{{ asset('storage/' . $pulang->foto) }}" width="40"
                                                    height="40" class="rounded shadow-sm" alt="Foto Pulang">
                                            @else
                                                <span class="badge bg-white"><i class="fas fa-info-circle me-1"></i>
                                                    {{ $pulang->foto }}</span>
                                            @endif
                                        @endif

                                        {{-- Case 3: Tidak ada data sama sekali --}}
                                        @if (!$masuk?->foto && !$pulang?->foto)
                                            <span class="text-muted">-</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column align-items-center gap-1">

                                        {{-- Logika untuk Absen Masuk --}}
                                        @if ($masuk?->lokasi)
                                            {{-- Cek apakah datanya adalah koordinat (mengandung ',') --}}
                                            @if (Str::contains($masuk->lokasi, ','))
                                                {{-- Format link diperbaiki agar langsung mencari koordinat --}}
                                                <a href="https://www.google.com/maps?q={{ $masuk->lokasi }}"
                                                    target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-map-marker-alt"></i> Masuk
                                                </a>
                                            @else
                                                <span class="badge bg-white"><i class="fas fa-info-circle me-1"></i>
                                                    {{ $masuk->lokasi }}</span>
                                            @endif
                                        @endif

                                        {{-- Logika untuk Absen Pulang --}}
                                        @if ($pulang?->lokasi)
                                            @if (Str::contains($pulang->lokasi, ','))
                                                <a href="https://www.google.com/maps?q={{ $pulang->lokasi }}"
                                                    target="_blank" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-map-marker-alt"></i> Pulang
                                                </a>
                                            @else
                                                <span class="badge bg-white"><i class="fas fa-info-circle me-1"></i>
                                                    {{ $pulang->lokasi }}</span>
                                            @endif
                                        @endif

                                        {{-- Case 3: Tidak ada data sama sekali --}}
                                        @if (!$masuk?->lokasi && !$pulang?->lokasi)
                                            <span class="text-muted">-</span>
                                        @endif
                                    </div>
                                </td>

                                {{-- Kolom Status --}}
                                <td>
                                    <span
                                        class="badge 
                        @if ($records->status_hadir == 'Hadir') bg-success
                        @elseif ($records->status_hadir == 'Hadir (Terlambat)') bg-warning text-dark
                        @elseif ($records->status_hadir == 'Tidak Hadir') bg-danger
                        @else bg-secondary @endif">
                                        {{ ucfirst($records->status_hadir) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-3x mb-3 text-secondary"></i>
                                    <p class="mb-0">Tidak ada data absensi.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-3 text-muted">
                    Menampilkan {{ $absensis->count() }} data absensi (semua user)
                </div>

            </div>

        </div>
    </div>

    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('absensi.data.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importModalLabel">Import Data Absensi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="fileImport" class="form-label">Pilih file (Excel/CSV) untuk diimpor:</label>

                            <input type="file" name="file" id="fileImport" class="form-control" required>
                        </div>
                        <small class="text-muted">
                            Pastikan format file Anda sesuai dengan template yang disediakan.
                        </small>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-file-import me-1"></i> Import Sekarang
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @push('scripts')
        <script>
            // Export Data Function
            function exportData() {
                const bulan = document.querySelector('[name="bulan"]').value;
                const tahun = document.querySelector('[name="tahun"]').value;
                const status = document.querySelector('[name="status"]').value;

                let exportUrl = "{{ route('absensi.data.exportAll', request()->query()) }}" + tahun;

                if (status) {
                    exportUrl += "&status=" + status;
                }

                console.log("Export URL:", exportUrl); // untuk debug
                window.location.href = exportUrl;
            }
        </script>
    @endpush
@endsection
