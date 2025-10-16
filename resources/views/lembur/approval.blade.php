@extends('layouts.app')

@section('title', 'Approval Lembur')

@section('content')
    <div class="container-fluid">

        <!-- Judul -->
        <h1 class="h3 mb-4 text-gray-800">Approval Lembur</h1>

        <!-- Filter -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <form action="{{ route('lembur.approval') }}" method="GET" class="row g-3 align-items-center">
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status Lembur</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="Menunggu" {{ request('status') == 'Menunggu' ? 'selected' : '' }}>Menunggu
                            </option>
                            <option value="Disetujui" {{ request('status') == 'Disetujui' ? 'selected' : '' }}>Disetujui
                            </option>
                            <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="bulan" class="form-label">Bulan</label>
                        <select name="bulan" id="bulan" class="form-select">
                            @php
                                $bulanList = [
                                    1 => 'Januari',
                                    2 => 'Februari',
                                    3 => 'Maret',
                                    4 => 'April',
                                    5 => 'Mei',
                                    6 => 'Juni',
                                    7 => 'Juli',
                                    8 => 'Agustus',
                                    9 => 'September',
                                    10 => 'Oktober',
                                    11 => 'November',
                                    12 => 'Desember',
                                ];
                            @endphp
                            @foreach ($bulanList as $num => $nama)
                                <option value="{{ $num }}"
                                    {{ request('bulan', now()->month) == $num ? 'selected' : '' }}>{{ $nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="tahun" class="form-label">Tahun</label>
                        <select name="tahun" id="tahun" class="form-select">
                            @for ($year = now()->year; $year >= now()->year - 3; $year--)
                                <option value="{{ $year }}"
                                    {{ request('tahun', now()->year) == $year ? 'selected' : '' }}>{{ $year }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter"></i> Terapkan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabel Data -->
        <div class="card shadow">
            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-primary text-center text-dark">
                        <tr>
                            <th>No</th>
                            <th>Departemen</th>
                            <th>Nama Karyawan</th>
                            <th>Nama Atasan</th>
                            <th>Tanggal</th>
                            <th>Shift</th>
                            <th>Mulai</th>
                            <th>Selesai</th>
                            <th>Job Description</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($lemburs as $index => $item)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $item->user->departement }}</td>
                                <td>{{ $item->user->name }}</td>
                                <td>{{ $item->approver?->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                                <td>{{ $item->jamKerja?->shift }}</td>
                                <td>{{ $item->tgl_jam_mulai }}</td>
                                <td>{{ $item->tgl_jam_selesai }}</td>
                                <td>{{ $item->deskripsi_kerja }}</td>
                                <td class="text-center">
                                    @if ($item->status_pengajuan == 'menunggu')
                                        <span class="badge bg-warning text-dark">Menunggu</span>
                                    @elseif ($item->status_pengajuan == 'disetujui')
                                        <span class="badge bg-success">Disetujui</span>
                                    @elseif ($item->status_pengajuan == 'ditolak')
                                        <span class="badge bg-danger">Ditolak</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="" class="btn btn-info btn-sm" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('lembur.approve', $item->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf

                                        @method('PUT')

                                        <button class="btn btn-success btn-sm" title="Setujui">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('lembur.reject', $item->id) }}" method="POST" class="d-inline">
                                        @csrf

                                        @method('PUT')
                                        <button class="btn btn-danger btn-sm" title="Tolak">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center text-muted">Belum ada data lembur.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
