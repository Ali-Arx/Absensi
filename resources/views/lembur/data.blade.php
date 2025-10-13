@extends('layouts.app')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Lembur</h1>
        <a href="{{ route('lembur.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Lembur
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <!-- Filter Card -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('lembur.data') }}" method="GET">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="date">Date</label>
                            <input type="date" class="form-control" id="date" name="date" value="{{ request('date') }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="department">Department</label>
                            <select class="form-control" id="department" name="department">
                                <option value="">Semua Department</option>
                                <option value="HR" {{ request('department') == 'HR' ? 'selected' : '' }}>HR</option>
                                <option value="IT" {{ request('department') == 'IT' ? 'selected' : '' }}>IT</option>
                                <option value="Finance" {{ request('department') == 'Finance' ? 'selected' : '' }}>Finance</option>
                                <option value="Marketing" {{ request('department') == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                                <option value="Operations" {{ request('department') == 'Operations' ? 'selected' : '' }}>Operations</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="month">Bulan</label>
                            <select class="form-control" id="month" name="month">
                                <option value="">Semua Bulan</option>
                                <option value="Januari" {{ request('month') == 'Januari' ? 'selected' : '' }}>Januari</option>
                                <option value="Februari" {{ request('month') == 'Februari' ? 'selected' : '' }}>Februari</option>
                                <option value="Maret" {{ request('month') == 'Maret' ? 'selected' : '' }}>Maret</option>
                                <option value="April" {{ request('month') == 'April' ? 'selected' : '' }}>April</option>
                                <option value="Mei" {{ request('month') == 'Mei' ? 'selected' : '' }}>Mei</option>
                                <option value="Juni" {{ request('month') == 'Juni' ? 'selected' : '' }}>Juni</option>
                                <option value="Juli" {{ request('month') == 'Juli' ? 'selected' : '' }}>Juli</option>
                                <option value="Agustus" {{ request('month') == 'Agustus' ? 'selected' : '' }}>Agustus</option>
                                <option value="September" {{ request('month') == 'September' ? 'selected' : '' }}>September</option>
                                <option value="Oktober" {{ request('month') == 'Oktober' ? 'selected' : '' }}>Oktober</option>
                                <option value="November" {{ request('month') == 'November' ? 'selected' : '' }}>November</option>
                                <option value="Desember" {{ request('month') == 'Desember' ? 'selected' : '' }}>Desember</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="year">Tahun</label>
                            <select class="form-control" id="year" name="year">
                                <option value="">Semua Tahun</option>
                                <option value="2023" {{ request('year') == '2023' ? 'selected' : '' }}>2023</option>
                                <option value="2024" {{ request('year') == '2024' ? 'selected' : '' }}>2024</option>
                                <option value="2025" {{ request('year') == '2025' ? 'selected' : '' }}>2025</option>
                                <option value="2026" {{ request('year') == '2026' ? 'selected' : '' }}>2026</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Terapkan Filter
                        </button>
                        <a href="{{ route('lembur.data') }}" class="btn btn-secondary">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Action Buttons & Search -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <a href="" class="btn btn-success btn-sm mr-2">
                        <i class="fas fa-file-excel"></i> Export
                    </a>
                    <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#importModal">
                        <i class="fas fa-file-upload"></i> Import
                    </button>
                </div>
                <div class="col-md-2 text-center">
                    <button class="btn btn-outline-secondary btn-sm" title="List View">
                        <i class="fas fa-th-list"></i>
                    </button>
                    <button class="btn btn-outline-secondary btn-sm" title="Grid View">
                        <i class="fas fa-th"></i>
                    </button>
                </div>
                <div class="col-md-6">
                    <form action="{{ route('lembur.data') }}" method="GET">
                        <div class="input-group">
                            <input type="text" class="form-control" name="search" placeholder="Cari nama atau job description..." value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- DataTales -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Lembur Karyawan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Plan</th>
                            <th>Actual</th>
                            <th>Paraf</th>
                            <th>Absen<br><small>(Fill by Admin)</small></th>
                            <th>OT Hours</th>
                            <th>SPV</th>
                            <th>Job Description</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lemburs as $index => $lembur)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <strong>{{ $lembur->user->name ?? 'N/A' }}</strong><br>
                                <small class="text-muted">{{ $lembur->user->no_karyawan ?? '-' }}</small>
                            </td>
                            <td>
                                @if($lembur->plan_start && $lembur->plan_end)
                                    <small>{{ \Carbon\Carbon::parse($lembur->plan_start)->format('H:i') }} - {{ \Carbon\Carbon::parse($lembur->plan_end)->format('H:i') }}</small><br>
                                    <span class="badge badge-info">{{ $lembur->plan_hours ?? 0 }} jam</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($lembur->actual_start && $lembur->actual_end)
                                    <small>{{ \Carbon\Carbon::parse($lembur->actual_start)->format('H:i') }} - {{ \Carbon\Carbon::parse($lembur->actual_end)->format('H:i') }}</small><br>
                                    <span class="badge badge-success">{{ $lembur->actual_hours ?? 0 }} jam</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <strong>{{ $lembur->paraf_hours ?? '0' }}</strong>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#absenModal{{ $lembur->id }}">
                                    <i class="fas fa-edit"></i> Fill
                                </button>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-warning">{{ $lembur->ot_hours ?? '0' }}</span>
                            </td>
                            <td>
                                @if($lembur->approver)
                                    {{ $lembur->approver->name }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <small>{{ Str::limit($lembur->job_description ?? '-', 50) }}</small>
                            </td>
                            <td>
                                @if($lembur->status == 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @elseif($lembur->status == 'approved')
                                    <span class="badge badge-success">Approved</span>
                                @elseif($lembur->status == 'rejected')
                                    <span class="badge badge-danger">Rejected</span>
                                @endif
                            </td>
                            <td>
                                <a href="" class="btn btn-info btn-sm" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center">Tidak ada data lembur</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if(method_exists($lemburs, 'links'))
            <div class="mt-3">
                {{ $lemburs->links() }}
            </div>
            @endif
        </div>
    </div>

</div>
<!-- /.container-fluid -->

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import Data Lembur</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>File Excel</label>
                        <input type="file" class="form-control-file" name="file" accept=".xlsx,.xls" required>
                        <small class="text-muted">Format: .xlsx atau .xls</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection