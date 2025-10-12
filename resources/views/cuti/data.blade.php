@extends('layouts.app') {{-- Pastikan layout sb-admin2 sudah digunakan --}}

@section('title', 'Data Cuti')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Cuti</h1>
    </div>

    <!-- Filter Section -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('cuti.data') }}" method="GET" class="row g-3 align-items-center">

                <div class="col-md-3">
                    <label for="department" class="form-label small fw-bold">Department</label>
                    <select name="department" id="department" class="form-select">
                        <option value="">Semua</option>
                        <option value="Staff">Staff</option>
                        <option value="Supervisor">Supervisor</option>
                        <option value="Manager">Manager</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="status" class="form-label small fw-bold">Status Cuti</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="Diajukan">Diajukan</option>
                        <option value="Disetujui">Disetujui</option>
                        <option value="Ditolak">Ditolak</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="bulan" class="form-label small fw-bold">Bulan</label>
                    <select name="bulan" id="bulan" class="form-select">
                        @foreach ([
                            'Januari','Februari','Maret','April','Mei','Juni',
                            'Juli','Agustus','September','Oktober','November','Desember'
                        ] as $b)
                            <option value="{{ $b }}">{{ $b }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="tahun" class="form-label small fw-bold">Tahun</label>
                    <select name="tahun" id="tahun" class="form-select">
                        @for ($y = now()->year; $y >= 2020; $y--)
                            <option value="{{ $y }}">{{ $y }}</option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-12 d-flex justify-content-end mt-3">
                    <button type="submit" class="btn btn-primary btn-sm me-2">
                        <i class="fas fa-filter me-1"></i> Terapkan
                    </button>
                    <button type="button" class="btn btn-success btn-sm me-2">
                        <i class="fas fa-file-export me-1"></i> Export
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm">
                        <i class="fas fa-file-import me-1"></i> Import
                    </button>
                </div>

            </form>
        </div>
    </div>

    <!-- Table Section -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-primary text-center">
                        <tr>
                            <th>No</th>
                            <th>No ID</th>
                            <th>Departemen</th>
                            <th>Nama</th>
                            <th>Nama Atasan</th>
                            <th>Jenis Cuti</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Mulai</th>
                            <th>Selesai</th>
                            <th>Alasan</th>
                            <th>Status</th>
                            <th>Tanggal Disetujui</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                       @forelse ($cutis as $cuti)
                            <tr>
                                <td class="text-center">{{ $cuti }}</td>
                                <td>{{ $cuti->id }}</td>
                                <td>{{ $cuti->departemen }}</td>
                                <td>{{ $cuti->nama_karyawan }}</td>
                                <td>{{ $cuti->nama_atasan }}</td>
                                <td>{{ $cuti->jenis_cuti }}</td>
                                <td>{{ \Carbon\Carbon::parse($cuti->tanggal_pengajuan)->format('d/m/Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($cuti->tanggal_mulai)->format('d/m/Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($cuti->tanggal_selesai)->format('d/m/Y') }}</td>
                                <td>{{ $cuti->alasan }}</td>
                                <td>
                                    @if($cuti->status == 'Disetujui')
                                        <span class="badge bg-success">Disetujui</span>
                                    @elseif($cuti->status == 'Diajukan')
                                        <span class="badge bg-warning text-dark">Diajukan</span>
                                    @else
                                        <span class="badge bg-danger">Ditolak</span>
                                    @endif
                                </td>
                                <td>{{ $cuti->tanggal_disetujui ? \Carbon\Carbon::parse($cuti->tanggal_disetujui)->format('d/m/Y') : '-' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('cuti.show', $cuti->id) }}" class="btn btn-sm btn-info text-white">
                                        <i class="fas fa-eye"></i> Lihat
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="13" class="text-center text-muted">Belum ada data cuti</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
