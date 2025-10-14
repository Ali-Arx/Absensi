@extends('layouts.app')

@section('title', 'Data Absensi - PT. Vortex Energy Batam')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Data Absensi</h1>
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
                                    @foreach (['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $b)
                                        <option value="{{ $b }}"
                                            {{ request('bulan', date('F')) == $b ? 'selected' : '' }}>
                                            {{ $b }}
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
                                    <option value="izin" {{ request('status') == 'izin' ? 'selected' : '' }}>Izin</option>
                                    <option value="sakit" {{ request('status') == 'sakit' ? 'selected' : '' }}>Sakit
                                    </option>
                                    <option value="alpha" {{ request('status') == 'alpha' ? 'selected' : '' }}>Alpha
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="search">Pencarian</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="search" name="search"
                                        placeholder="Cari tanggal, waktu, atau keterangan..."
                                        value="{{ request('search') }}">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
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
                            <form action="{{ route('absensi.data.import') }}" method="POST" enctype="multipart/form-data"
                                class="d-inline">
                                @csrf
                                <input type="file" name="file" id="fileImport" hidden onchange="this.form.submit()">
                                <button type="button" class="btn btn-success me-2"
                                    onclick="document.getElementById('fileImport').click()">
                                    <i class="fas fa-file-import me-1"></i> Import
                                </button>
                            </form>

                            {{-- Export --}}
                            <a href="{{ route('absensi.data.export') }}" class="btn btn-info">
                                <i class="fas fa-file-export me-1"></i> Export
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered text-center align-middle">
                    <thead class="bg-light text-dark fw-semibold">
                        <tr>
                            <th>No</th>
                            <th>Departemen</th>
                            <th>Nama</th>
                            <th>No ID</th>
                            <th>Tanggal</th>
                            <th>Waktu In | Out</th>
                            <th>Total Jam</th>
                            <th>Kode Verifikasi</th>
                            <th>Lokasi</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($absensis as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->user->departement ?? '-' }}</td>
                                <td>{{ $item->user->name ?? '-' }}</td>
                                <td>{{ $item->user->badge_number ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal_waktu)->format('d/m/Y') }}</td>

                                {{-- Kolom Waktu In | Out --}}
                                <td>
                                    <div class="d-flex flex-column">
                                        <span>{{ $item->waktu_in ? \Carbon\Carbon::parse($item->waktu_in)->format('H:i') : '-' }}</span>
                                        <span class="text-muted">|</span>
                                        <span>{{ $item->waktu_out ? \Carbon\Carbon::parse($item->waktu_out)->format('H:i') : '-' }}</span>
                                    </div>
                                </td>

                                {{-- Kolom Total Jam --}}
                                <td>{{ $item->total_jam ?? '-' }}</td>

                                {{-- Kolom Kode Verifikasi --}}
                                <td>
                                    <div class="d-flex flex-column align-items-center gap-1">
                                        @if ($item->kode_verifikasi_in)
                                            <img src="{{ asset('storage/' . $item->kode_verifikasi_in) }}" width="40"
                                                height="40" class="rounded shadow-sm" style="object-fit: cover;">
                                        @endif
                                        @if ($item->kode_verifikasi_out)
                                            <img src="{{ asset('storage/' . $item->kode_verifikasi_out) }}" width="40"
                                                height="40" class="rounded shadow-sm" style="object-fit: cover;">
                                        @endif
                                        @if (!$item->kode_verifikasi_in && !$item->kode_verifikasi_out)
                                            <span class="text-muted">-</span>
                                        @endif
                                    </div>
                                </td>

                                {{-- Kolom Lokasi --}}
                                <td>
                                    <div class="d-flex flex-column align-items-center gap-1">
                                        @if ($item->lokasi_in)
                                            <a href="https://www.google.com/maps?q={{ $item->lokasi_in }}" target="_blank"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-map-marker-alt"></i> Masuk
                                            </a>
                                        @endif
                                        @if ($item->lokasi_out)
                                            <a href="https://www.google.com/maps?q={{ $item->lokasi_out }}" target="_blank"
                                                class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-map-marker-alt"></i> Pulang
                                            </a>
                                        @endif
                                        @if (!$item->lokasi_in && !$item->lokasi_out)
                                            <span class="text-muted">-</span>
                                        @endif
                                    </div>
                                </td>

                                {{-- Kolom Keterangan --}}
                                <td>
                                    <span
                                        class="badge 
                            @if ($item->keterangan == 'Hadir') bg-success
                            @elseif ($item->keterangan == 'Terlambat') bg-warning text-dark
                            @elseif ($item->keterangan == 'Izin') bg-info
                            @elseif ($item->keterangan == 'Sakit') bg-secondary
                            @else bg-danger @endif">
                                        {{ ucfirst($item->keterangan ?? '-') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-3x mb-3 text-secondary"></i>
                                    <p class="mb-0">Tidak ada data absensi.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>


            {{-- Pagination --}}
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Menampilkan {{ $absensis->count() }} dari {{ $absensis->total() }} data
                </div>
                <div>
                    {{ $absensis->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
