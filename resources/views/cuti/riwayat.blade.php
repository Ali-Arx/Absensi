@extends('layouts.app')

@section('title', 'Riwayat Cuti')

@section('content')
    <div class="container-fluid">

        <!-- Judul Halaman -->
        <h4 class="mb-4 text-gray-800 fw-bold">Riwayat Cuti</h4>

        <!-- Filter -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form action="{{ route('cuti.riwayat') }}" method="GET" class="row g-3 align-items-end">

                    {{-- Filter Status Cuti --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Status Cuti</label>
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>

                    {{-- Filter Bulan --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Bulan</label>
                        <select name="bulan" class="form-select" id="bulanFilter">
                            <option value="1" {{ request('bulan', date('n')) == 1 ? 'selected' : '' }}>Januari</option>
                            <option value="2" {{ request('bulan', date('n')) == 2 ? 'selected' : '' }}>Februari</option>
                            <option value="3" {{ request('bulan', date('n')) == 3 ? 'selected' : '' }}>Maret</option>
                            <option value="4" {{ request('bulan', date('n')) == 4 ? 'selected' : '' }}>April</option>
                            <option value="5" {{ request('bulan', date('n')) == 5 ? 'selected' : '' }}>Mei</option>
                            <option value="6" {{ request('bulan', date('n')) == 6 ? 'selected' : '' }}>Juni</option>
                            <option value="7" {{ request('bulan', date('n')) == 7 ? 'selected' : '' }}>Juli</option>
                            <option value="8" {{ request('bulan', date('n')) == 8 ? 'selected' : '' }}>Agustus</option>
                            <option value="9" {{ request('bulan', date('n')) == 9 ? 'selected' : '' }}>September</option>
                            <option value="10" {{ request('bulan', date('n')) == 10 ? 'selected' : '' }}>Oktober</option>
                            <option value="11" {{ request('bulan', date('n')) == 11 ? 'selected' : '' }}>November</option>
                            <option value="12" {{ request('bulan', date('n')) == 12 ? 'selected' : '' }}>Desember</option>
                        </select>
                    </div>

                    {{-- Filter Tahun --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Tahun</label>
                        <select name="tahun" class="form-select" id="tahunFilter">
                            @for ($i = 2020; $i <= 2030; $i++)
                                <option value="{{ $i }}" {{ request('tahun', date('Y')) == $i ? 'selected' : '' }}>
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    {{-- Tombol Terapkan --}}
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter me-1"></i> Terapkan
                        </button>
                    </div>

                </form>
            </div>
        </div>

        <!-- Tabel Riwayat Cuti -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle text-center">
                        <thead class="table-primary text-dark">
                            <tr>
                                <th>No</th>
                                <th>No ID</th>
                                <th>Departement</th>
                                <th>Nama</th>
                                <th>Nama Atasan</th>
                                <th>Jenis Cuti</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Selesai</th>
                                <th>Alasan</th>
                                <th>Status</th>
                                <th>Tanggal Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($cutis as $cuti)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $cuti->user->badge_number }}</td>
                                    <td>{{ $cuti->user->departement }}</td>
                                    <td>{{ $cuti->user->name }}</td>
                                    <td>{{ $cuti->nama_atasan }}</td>
                                    <td>{{ $cuti->jenis_cuti }}</td>
                                    <td>{{ \Carbon\Carbon::parse($cuti->tgl_pengajuan)->format('d M Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($cuti->mulai)->format('d M Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($cuti->selesai)->format('d M Y') }}</td>
                                    <td>{{ $cuti->alasan }}</td>
                                    <td>
                                        @if ($cuti->status_pengajuan == 'menunggu')
                                            <span class="badge bg-warning text-dark">Menunggu</span>
                                        @elseif($cuti->status_pengajuan == 'disetujui')
                                            <span class="badge bg-success">Disetujui</span>
                                        @elseif($cuti->status_pengajuan == 'ditolak')
                                            <span class="badge bg-danger">Ditolak</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($cuti->tgl_status)
                                            {{ \Carbon\Carbon::parse($cuti->tgl_status)->format('d M Y') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-info btn-sm text-white" 
                                                data-bs-toggle="modal"
                                                data-bs-target="#detailModal" 
                                                data-cuti='@json($cuti)'>
                                            <i class="fas fa-eye"></i> Lihat
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="13" class="text-center text-muted py-3">Belum ada data cuti</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <!-- Modal Detail -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Cuti</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Tanggal Pengajuan:</strong> <span id="d_pengajuan"></span></p>
                    <p><strong>Tanggal Cuti:</strong> <span id="d_cuti"></span></p>
                    <p><strong>Jenis Cuti:</strong> <span id="d_jenis"></span></p>
                    <p><strong>Alasan:</strong> <span id="d_alasan"></span></p>
                    <p><strong>Status:</strong> <span id="d_status"></span></p>
                    <p><strong>Komentar Admin:</strong> <span id="d_komentar"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        console.log('Script loaded');
        
        const modal = document.getElementById('detailModal');
        console.log('Modal element:', modal);
        
        if (modal) {
            modal.addEventListener('show.bs.modal', event => {
                console.log('Modal event triggered');
                
                const button = event.relatedTarget;
                console.log('Button clicked:', button);
                
                const cutiData = button.getAttribute('data-cuti');
                console.log('Raw data-cuti:', cutiData);
                
                try {
                    const cuti = JSON.parse(cutiData);
                    console.log('Parsed cuti:', cuti);
                    
                    // Sesuaikan dengan nama field dari database
                    document.getElementById('d_pengajuan').textContent = new Date(cuti.tgl_pengajuan)
                        .toLocaleDateString('id-ID');
                    document.getElementById('d_cuti').textContent =
                        `${new Date(cuti.mulai).toLocaleDateString('id-ID')} s/d ${new Date(cuti.selesai).toLocaleDateString('id-ID')}`;
                    document.getElementById('d_jenis').textContent = cuti.jenis_cuti || '-';
                    document.getElementById('d_alasan').textContent = cuti.alasan || '-';
                    
                    // Tampilkan status dengan format yang bagus
                    let statusText = '-';
                    if (cuti.status_pengajuan === 'menunggu') {
                        statusText = 'Menunggu';
                    } else if (cuti.status_pengajuan === 'disetujui') {
                        statusText = 'Disetujui';
                    } else if (cuti.status_pengajuan === 'ditolak') {
                        statusText = 'Ditolak';
                    }
                    document.getElementById('d_status').textContent = statusText;
                    
                    document.getElementById('d_komentar').textContent = cuti.komentar_admin || '-';
                    
                    console.log('Modal populated successfully');
                } catch (error) {
                    console.error('Error parsing JSON:', error);
                }
            });
        } else {
            console.error('Modal element not found!');
        }
    </script>
@endpush