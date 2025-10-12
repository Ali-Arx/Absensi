@extends('layouts.app')

@section('title', 'Riwayat Absensi - PT. Vortex Energy Batam')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Riwayat Absensi</h1>
    </div>

    <div class="card shadow mb-4 border-0">
        <div class="card-body">
            {{-- Filter Section --}}
            <form method="GET" action="{{ route('absensi.riwayat') }}" id="filterForm">
                <div class="row mb-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Bulan</label>
                        <select name="bulan" class="form-select" id="bulanFilter" onchange="applyFilter()">
                            <option value="1" {{ request('bulan', date('n')) == 1 ? 'selected' : '' }}>Januari</option>
                            <option value="2" {{ request('bulan', date('n')) == 2 ? 'selected' : '' }}>Februari
                            </option>
                            <option value="3" {{ request('bulan', date('n')) == 3 ? 'selected' : '' }}>Maret</option>
                            <option value="4" {{ request('bulan', date('n')) == 4 ? 'selected' : '' }}>April</option>
                            <option value="5" {{ request('bulan', date('n')) == 5 ? 'selected' : '' }}>Mei</option>
                            <option value="6" {{ request('bulan', date('n')) == 6 ? 'selected' : '' }}>Juni</option>
                            <option value="7" {{ request('bulan', date('n')) == 7 ? 'selected' : '' }}>Juli</option>
                            <option value="8" {{ request('bulan', date('n')) == 8 ? 'selected' : '' }}>Agustus</option>
                            <option value="9" {{ request('bulan', date('n')) == 9 ? 'selected' : '' }}>September
                            </option>
                            <option value="10" {{ request('bulan', date('n')) == 10 ? 'selected' : '' }}>Oktober
                            </option>
                            <option value="11" {{ request('bulan', date('n')) == 11 ? 'selected' : '' }}>November
                            </option>
                            <option value="12" {{ request('bulan', date('n')) == 12 ? 'selected' : '' }}>Desember
                            </option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Tahun</label>
                        <input type="number" name="tahun" class="form-control" value="{{ request('tahun', date('Y')) }}"
                            min="2020" max="2030" onchange="applyFilter()">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select" onchange="applyFilter()">
                            <option value="" {{ request('status') == '' ? 'selected' : '' }}>Semua Status</option>
                            <option value="hadir" {{ request('status') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                            <option value="belum_pulang" {{ request('status') == 'belum_pulang' ? 'selected' : '' }}>
                                Terlambat</option>
                            <option value="tidak_hadir" {{ request('status') == 'tidak_hadir' ? 'selected' : '' }}>Tidak
                                Hadir</option>
                        </select>
                    </div>

                    <div class="col-md-3 text-end">
                        <button type="button" class="btn btn-outline-secondary me-2" onclick="refreshData()"
                            title="Refresh">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                        <button type="button" class="btn btn-outline-secondary me-2" onclick="toggleSort()"
                            title="Urutkan">
                            <i class="fas fa-sort-amount-down" id="sortIcon"></i>
                        </button>
                        <button type="button" class="btn btn-primary" onclick="exportData()" title="Export">
                            <i class="fas fa-file-export me-1"></i> Export
                        </button>
                        <input type="hidden" name="sort" id="sortInput" value="{{ request('sort', 'desc') }}">
                    </div>
                </div>
            </form>

            {{-- Search Bar --}}
            <div class="row mb-3">
                <div class="col-md-4 ms-auto">
                    <input type="text" class="form-control" id="searchInput"
                        placeholder="Cari tanggal, waktu, atau keterangan..." onkeyup="searchTable()">
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

                                // Tentukan status
                                if ($masuk && $pulang) {
                                    $statusKet = 'hadir';
                                } elseif ($masuk && !$pulang) {
                                    $statusKet = 'belum_pulang';
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
                                    @if ($masuk && $pulang)
                                        <span class="badge bg-success">Hadir</span>
                                    @elseif ($masuk && !$pulang)
                                        <span class="badge bg-warning text-dark">Belum Pulang</span>
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

            function exportData() {
                alert('Export data masih dalam pengembangan 🛠️');
            }
        </script>
    @endpush
@endsection
