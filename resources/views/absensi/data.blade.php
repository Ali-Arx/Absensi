@extends('layouts.app')

@section('title', 'Data Absensi')

@section('content')
    <div class="container-fluid py-4">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold text-gray-800">Data Absensi</h4>
            <div>
                <button class="btn btn-success btn-sm"><i class="fas fa-file-import me-1"></i> Import</button>
                <button class="btn btn-primary btn-sm"><i class="fas fa-file-export me-1"></i> Export</button>
            </div>
        </div>

        <!-- Filter -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form action="{{ route('absensi.data') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Department</label>
                        <select class="form-select" name="department">
                            <option value="">Semua Department</option>
                            <option value="Engineering">Engineering</option>
                            <option value="Production">Production</option>
                            <option value="Staff">Staff</option>
                            <option value="Sales">Sales</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Bulan</label>
                        <select class="form-select" name="bulan">
                            <option>Januari</option>
                            <option>Februari</option>
                            <option>Maret</option>
                            <option>April</option>
                            <option>Mei</option>
                            <option>Juni</option>
                            <option>Juli</option>
                            <option>Agustus</option>
                            <option>September</option>
                            <option>Oktober</option>
                            <option>November</option>
                            <option>Desember</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Tahun</label>
                        <select class="form-select" name="tahun">
                            @for ($year = now()->year; $year >= 2020; $year--)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-center">
                        <button type="submit" class="btn btn-primary me-2"><i class="fas fa-filter me-1"></i> </button>
                        <button type="button" class="btn btn-outline-secondary me-2"><i
                                class="fas fa-sync-alt me-1"></i></button>
                        <div class="flex-grow-1">
                            <input type="text" class="form-control" placeholder="Search...">
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabel Data Absensi -->
        {{-- Table Section --}}
        <div class="table-responsive">
            <table class="table table-bordered align-middle text-center" id="absensiTable">
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
                    @forelse ($absensis as $tanggal => $records)
                        @php
                            $masuk = $records->firstWhere('tipe_absen', 'masuk');
                            $pulang = $records->firstWhere('tipe_absen', 'pulang');

                            // Tentukan status
                            if ($masuk && $pulang) {
                                $statusKet = 'hadir';
                            } elseif ($masuk && !$pulang) {
                                $statusKet = 'terlambat';
                            } else {
                                $statusKet = 'tidak_hadir';
                            }
                        @endphp
                        <tr data-status="{{ $statusKet }}">
                            <td>{{ $loop->iteration + ($absensis->currentPage() - 1) * $absensis->perPage() }}</td>
                            <td>{{ $user->departement ?? '-' }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->badge_number ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($tanggal)->format('d/m/y') }}</td>
                            <td>
                                {{ $masuk ? date('H:i', strtotime($masuk->tanggal_waktu)) : '-' }}
                                |
                                {{ $pulang ? date('H:i', strtotime($pulang->tanggal_waktu)) : '-' }}
                            </td>
                            <td>
                                @if ($masuk && $pulang)
                                    {{ gmdate('H:i:s', strtotime($pulang->tanggal_waktu) - strtotime($masuk->tanggal_waktu)) }}
                                @else
                                    -
                                @endif
                            </td>

                            {{-- Foto Masuk dan Keluar --}}
                            <td>
                                @if ($masuk && $masuk->foto)
                                    <img src="{{ asset('storage/' . $masuk->foto) }}" alt="Foto Masuk"
                                        class="rounded shadow-sm mb-1" width="70" height="70"
                                        style="object-fit: cover; cursor: pointer;"
                                        onclick="showImageModal('{{ asset('storage/' . $masuk->foto) }}', 'Foto Masuk')">
                                @else
                                    <span class="text-muted d-block">-</span>
                                @endif

                                @if ($pulang && $pulang->foto)
                                    <img src="{{ asset('storage/' . $pulang->foto) }}" alt="Foto Pulang"
                                        class="rounded shadow-sm" width="70" height="70"
                                        style="object-fit: cover; cursor: pointer;"
                                        onclick="showImageModal('{{ asset('storage/' . $pulang->foto) }}', 'Foto Pulang')">
                                @else
                                    <span class="text-muted d-block">-</span>
                                @endif
                            </td>

                            {{-- Lokasi Masuk dan Pulang --}}
                            <td>
                                @if ($masuk && $masuk->lokasi)
                                    @php [$lat, $lng] = explode(',', $masuk->lokasi); @endphp
                                    <a href="https://www.google.com/maps?q={{ $lat }},{{ $lng }}"
                                        target="_blank" class="btn btn-sm btn-outline-primary mb-1">
                                        <i class="fas fa-map-marker-alt"></i> Masuk
                                    </a>
                                @else
                                    <span class="text-muted d-block">-</span>
                                @endif

                                @if ($pulang && $pulang->lokasi)
                                    @php [$lat, $lng] = explode(',', $pulang->lokasi); @endphp
                                    <a href="https://www.google.com/maps?q={{ $lat }},{{ $lng }}"
                                        target="_blank" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-map-marker-alt"></i> Pulang
                                    </a>
                                @else
                                    <span class="text-muted d-block">-</span>
                                @endif
                            </td>

                            {{-- Keterangan --}}
                            <td>
                                @if (isset($masuk))
                                    @php
                                        $jamMasukNormal = \Carbon\Carbon::parse(
                                            $masuk->jamKerja->jam_masuk ?? '08:00:00',
                                        );
                                        $jamMasukNyata = \Carbon\Carbon::parse($masuk->tanggal_waktu);
                                    @endphp

                                    @if ($jamMasukNyata->gt($jamMasukNormal))
                                        <span class="badge bg-warning text-dark">Hadir (Terlambat)</span>
                                    @else
                                        <span class="badge bg-success">Hadir</span>
                                    @endif
                                @else
                                    <span class="badge bg-danger">Tidak Hadir</span>
                                @endif
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-3x mb-3 text-secondary"></i>
                                <p class="mb-0">Tidak ada data absensi untuk periode ini.</p>
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

    {{-- Modal untuk melihat foto lebih besar --}}
    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Foto Absensi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" alt="Foto" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </div>

    </div>
@endsection
