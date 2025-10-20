@extends('layouts.app')

@section('title', 'Riwayat Absensi - PT. Vortex Energy Batam')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Riwayat Absensi</h1>
    </div>

    <div class="card shadow mb-4 border-0">
        <div class="card-body">
            {{-- Filter & Search Section --}}
            <div class="card shadow mb-4">
                <div class="card-body">
                    <form id="filterForm" action="{{ route('absensi.riwayat') }}" method="GET">
                        <div class="row align-items-end">
                            {{-- Bulan --}}
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

                            {{-- Tahun --}}
                            <div class="col-md-2">
                                <label for="tahun">Tahun</label>
                                <input type="number" class="form-control" id="tahun" name="tahun"
                                    value="{{ request('tahun', date('Y')) }}" min="2020" max="2099">
                            </div>

                            {{-- Status --}}
                            <div class="col-md-3">
                                <label for="status">Status</label>t
                                <select class="form-control" id="status" name="status">
                                    <option value="">Semua Status</option>

                                    <option value="Hadir" {{ request('status') == 'Hadir' ? 'selected' : '' }}>
                                        Hadir
                                    </option>
                                    <option value="Hadir (Terlambat)"
                                        {{ request('status') == 'Hadir (Terlambat)' ? 'selected' : '' }}>
                                        Terlambat
                                    </option>
                                    <option value="Tidak Hadir" {{ request('status') == 'Tidak Hadir' ? 'selected' : '' }}>
                                        Tidak Hadir
                                    </option>
                                </select>
                            </div>

                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="mt-3 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-filter me-1"></i> Terapkan
                            </button>

                            <a href="{{ route('absensi.riwayat') }}" class="btn btn-secondary me-2">
                                <i class="fas fa-sync-alt me-1"></i> Reset
                            </a>

                            <button type="button" class="btn btn-success" onclick="exportData()">
                                <i class="fas fa-file-export me-1"></i> Export
                            </button>
                        </div>
                    </form>
                </div>
            </div>


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
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration + ($absensis->currentPage() - 1) * $absensis->perPage() }}</td>
                                <td>{{ $user->departement ?? '-' }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->badge_number ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($tanggal)->format('d/m/Y') }}</td>
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

                                {{-- Foto Masuk & Pulang --}}
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

                                {{-- Keterangan --}}
                                <td>
                                    @if ($masuk)
                                        @if ($masuk->keterangan_dinamis === 'Hadir (Terlambat)')
                                            <span class="badge bg-warning text-dark">Hadir (Terlambat)</span>
                                        @elseif ($masuk->keterangan_dinamis === 'Hadir')
                                            <span class="badge bg-success">Hadir</span>
                                        @else
                                            <span class="badge bg-danger">Tidak Hadir</span>
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

    @push('scripts')
        <script>
            // Apply Filter Function
            function applyFilter() {
                document.getElementById('filterForm').submit();
            }

            // Refresh Data - Reset ke bulan dan tahun sekarang
            function refreshData() {
                const currentMonth = {{ date('n') }};
                const currentYear = {{ date('Y') }};
                window.location.href = "{{ route('absensi.riwayat') }}?bulan=" + currentMonth + "&tahun=" + currentYear;
            }

            // Toggle Sort
            function toggleSort() {
                const currentSort = document.getElementById('sortInput').value;
                const newSort = currentSort === 'asc' ? 'desc' : 'asc';
                document.getElementById('sortInput').value = newSort;

                const icon = document.getElementById('sortIcon');
                icon.className = newSort === 'asc' ? 'fas fa-sort-amount-up' : 'fas fa-sort-amount-down';

                applyFilter();
            }

            // Search Table
            function searchTable() {
                const input = document.getElementById('searchInput');
                const filter = input.value.toUpperCase();
                const table = document.getElementById('absensiTable');
                const tr = table.getElementsByTagName('tr');

                for (let i = 1; i < tr.length; i++) {
                    let found = false;
                    const td = tr[i].getElementsByTagName('td');

                    // Search in multiple columns: Tanggal (4), Waktu (5), Keterangan (9)
                    const searchColumns = [4, 5, 9];

                    for (let colIndex of searchColumns) {
                        if (td[colIndex]) {
                            const txtValue = td[colIndex].textContent || td[colIndex].innerText;
                            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                                found = true;
                                break;
                            }
                        }
                    }

                    tr[i].style.display = found ? '' : 'none';
                }
            }

            // Export Data Function
            function exportData() {
                const bulan = document.querySelector('[name="bulan"]').value;
                const tahun = document.querySelector('[name="tahun"]').value;
                const status = document.querySelector('[name="status"]').value;

                let exportUrl = "{{ route('absensi.riwayat.export') }}?bulan=" + bulan + "&tahun=" + tahun;

                if (status) {
                    exportUrl += "&status=" + status;
                }

                console.log("Export URL:", exportUrl); // untuk debug
                window.location.href = exportUrl;
            }

            // Show Image Modal
            function showImageModal(imageSrc, title) {
                document.getElementById('modalImage').src = imageSrc;
                document.getElementById('imageModalLabel').textContent = title;
                const modal = new bootstrap.Modal(document.getElementById('imageModal'));
                modal.show();
            }

            // Set icon sort on page load
            document.addEventListener('DOMContentLoaded', function() {
                const currentSort = "{{ request('sort', 'desc') }}";
                const icon = document.getElementById('sortIcon');
                icon.className = currentSort === 'asc' ? 'fas fa-sort-amount-up' : 'fas fa-sort-amount-down';
            });

            function applyFilter() {
                document.getElementById('filterForm').submit();
            }

            function refreshData() {
                window.location.href = "{{ route('absensi.riwayat') }}";
            }

            function toggleSort() {
                const sortInput = document.getElementById('sortInput');
                const sortIcon = document.getElementById('sortIcon');
                sortInput.value = sortInput.value === 'desc' ? 'asc' : 'desc';
                sortIcon.classList.toggle('fa-sort-amount-down');
                sortIcon.classList.toggle('fa-sort-amount-up');
                document.getElementById('filterForm').submit();
            }
        </script>
    @endpush
@endsection
