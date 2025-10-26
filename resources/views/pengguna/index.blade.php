@extends('layouts.app')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Data Pengguna</h1>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {!! session('error') !!} {{-- Kita pakai {!! !!} agar <br> bisa ter-render --}}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Menampilkan Error Validasi (PENTING!) --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error Validasi!</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Filter Card -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('pengguna.index') }}" id="filterForm">
                    <div class="row">
                        <!-- Date Filter -->
                        <div class="col-lg-3 col-md-6 mb-3">
                            <label for="date" class="form-label font-weight-bold">Date</label>
                            <input type="date" class="form-control" id="date" name="date"
                                value="{{ request('date') }}">
                        </div>

                        <!-- Department Filter -->
                        <div class="col-lg-3 col-md-6 mb-3">
                            <label for="department" class="form-label font-weight-bold">Department</label>
                            <select class="form-control" id="department" name="department">
                                <option value="">Staff</option>
                                <option value="HR" {{ request('department') == 'Office' ? 'selected' : '' }}>Office
                                </option>
                                <option value="IT" {{ request('department') == 'Sales' ? 'selected' : '' }}>Sales
                                </option>
                                <option value="Finance" {{ request('department') == 'Productionh' ? 'selected' : '' }}>
                                    Production
                                <option value="HR" {{ request('department') == 'Office' ? 'selected' : '' }}>Office</option>
                                <option value="IT" {{ request('department') == 'Sales' ? 'selected' : '' }}>Sales</option>
                                <option value="Finance" {{ request('department') == 'Productionh' ? 'selected' : '' }}>Productionh
                                </option>
                                <option value="Operations" {{ request('department') == 'Operations' ? 'selected' : '' }}>
                                    Operations</option>
                            </select>
                        </div>

                        <!-- Year Filter -->
                        <div class="col-lg-3 col-md-6 mb-3">
                            <label for="tahun" class="form-label font-weight-bold">Tahun</label>
                            <select class="form-control" id="tahun" name="tahun">
                                <option value="2025" {{ request('tahun') == '2025' ? 'selected' : '' }}>2025</option>
                                <option value="2024" {{ request('tahun') == '2024' ? 'selected' : '' }}>2024</option>
                                <option value="2023" {{ request('tahun') == '2023' ? 'selected' : '' }}>2023</option>
                            </select>
                        </div>

                        <!-- Add Data Button -->
                        <div class="col-lg-3 col-md-6 mb-3">
                            <label class="form-label font-weight-bold d-block">&nbsp;</label>
                            <button type="button" class="btn btn-primary btn-block" data-toggle="modal"
                                data-target="#tambahDataModal">
                                <i class="fas fa-plus"></i> Tambah Data
                            </button>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-info">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <a href="{{ route('pengguna.index') }}" class="btn btn-secondary">
                                <i class="fas fa-redo"></i> Reset
                            </a>
                            <button type="button" class="btn btn-success me-2" data-bs-toggle="modal"
                                data-bs-target="#importModal">
                                <i class="fas fa-file-import me-1"></i> Import
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>

        <!-- DataTales Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Data Pengguna</h6>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-sm btn-outline-primary" id="gridViewBtn">
                        <i class="fas fa-th"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-primary active" id="listViewBtn">
                        <i class="fas fa-list"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center" style="width: 50px;">No</th>
                                <th style="width: 100px;">Emp No</th>
                                <th style="width: 120px;">Department</th>
                                <th style="width: 120px;">Name</th>
                                <th style="width: 130px;">Join Date</th>
                                <th style="width: 150px;">Position</th>
                                <th style="width: 120px;">No Hp</th>
                                <th class="text-center" style="width: 100px;">Status</th>
                                <th class="text-center" style="width: 180px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users ?? [] as $index => $user)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $user->badge_number ?? '-' }}</td>
                                    <td>{{ $user->departement ?? '-' }}</td>
                                    <td>{{ $user->name ?? '-' }}</td>
                                    <td>{{ $user->join_date ? date('d/m/Y', strtotime($user->join_date)) : '-' }}</td>
                                    <td>{{ $user->jabatan ?? '-' }}</td>
                                    <td>{{ $user->No_HP ?? '-' }}</td>
                                    <td class="text-center">
                                        @if (($user->status ?? '') == 'active')
                                            <span class="badge badge-success px-2 py-1">Active</span>
                                        @else
                                            <span class="badge badge-danger px-2 py-1">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-warning" data-toggle="modal"
                                                data-target="#editModal{{ $user->id }}" title="Edit">
                                                Edit
                                            </button>
                                            <button type="button" class="btn btn-sm btn-info" data-toggle="modal"
                                                data-target="#detailModal{{ $user->id }}" title="Detail">
                                                Detail
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger"
                                                onclick="confirmDelete({{ $user->id }})" title="Hapus">
                                                Del
                                            </button>
                                            <div class="modal fade" id="detailModal{{ $user->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="detailModalLabel{{ $user->id }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-info text-white">
                                                            <h5 class="modal-title"
                                                                id="detailModalLabel{{ $user->id }}">
                                                                <i class="fas fa-info-circle"></i> Detail Pengguna
                                                            </h5>
                                                            <button type="button" class="close text-white"
                                                                data-dismiss="modal" aria-label="Close">
                                                                <span>&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <table class="table table-bordered table-striped">
                                                                <tbody>
                                                                    <tr>
                                                                        <th width="30%" class="bg-light">No ID</th>
                                                                        <td>{{ $user->badge_number ?? '-' }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="bg-light">Nama</th>
                                                                        <td>{{ $user->name ?? '-' }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="bg-light">Email</th>
                                                                        <td>{{ $user->email ?? '-' }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="bg-light">Departemen</th>
                                                                        <td>{{ $user->departement ?? '-' }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="bg-light">Jabatan</th>
                                                                        <td>{{ $user->jabatan ?? '-' }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="bg-light">Tanggal Masuk</th>
                                                                        <td>{{ $user->join_date ? date('d F Y', strtotime($user->join_date)) : '-' }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="bg-light">No HP</th>
                                                                        <td>{{ $user->No_HP ?? '-' }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="bg-light">Status</th>
                                                                        <td>
                                                                            @if ($user->status == 'active')
                                                                                <span
                                                                                    class="badge badge-success px-3 py-2">Active</span>
                                                                            @else
                                                                                <span
                                                                                    class="badge badge-danger px-3 py-2">Inactive</span>
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="bg-light">Role</th>
                                                                        <td>{{ ucfirst($user->role) ?? '-' }}</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">
                                                                <i class="fas fa-times"></i> Tutup
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <!-- /.container-fluid -->
                                <div class="modal fade" id="editModal{{ $user->id }}" tabindex="-1"
                                    role="dialog">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-warning text-white">
                                                <h5 class="modal-title">
                                                    <i class="fas fa-edit"></i> Edit Data Pengguna
                                                </h5>
                                                <button type="button" class="close text-white" data-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                            </div>
                                            <form method="POST" action="{{ route('pengguna.update', $user->id) }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="font-weight-bold">No ID <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="text" class="form-control"
                                                                    name="badge_number" value="{{ $user->badge_number }}"
                                                                    required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="font-weight-bold">Nama <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" name="name"
                                                                    value="{{ $user->name }}" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="font-weight-bold">Email <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="email" class="form-control" name="email"
                                                                    value="{{ $user->email }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="font-weight-bold">Password <small
                                                                        class="text-muted">(Kosongkan jika
                                                                        tidak
                                                                        diubah)</small></label>
                                                                <input type="password" class="form-control"
                                                                    name="password" placeholder="Masukkan password baru">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="font-weight-bold">Departmen <span
                                                                        class="text-danger">*</span></label>
                                                                <select class="form-control" name="departement" required>
                                                                    <option value="">Pilih Departmen</option>
                                                                    <option value="Office"
                                                                        {{ $user->departement == 'Office' ? 'selected' : '' }}>
                                                                        Office</option>
                                                                    <option value="Sales"
                                                                        {{ $user->departement == 'Sales' ? 'selected' : '' }}>
                                                                        Sales</option>
                                                                    <option value="Production"
                                                                        {{ $user->departement == 'Production' ? 'selected' : '' }}>
                                                                        Production</option>
                                                                    <option value="Engineering"
                                                                        {{ $user->departement == 'Engineering' ? 'selected' : '' }}>
                                                                        Engineering</option>
                                                                    <option value="HR"
                                                                        {{ $user->department == 'HR' ? 'selected' : '' }}>
                                                                        HR</option>
                                                                    <option value="IT"
                                                                        {{ $user->department == 'IT' ? 'selected' : '' }}>
                                                                        IT</option>
                                                                    <option value="Productionh"
                                                                        {{ $user->department == 'Finance' ? 'selected' : '' }}>
                                                                        Finance</option>
                                                                    <option value="Operations"
                                                                        {{ $user->department == 'Operations' ? 'selected' : '' }}>
                                                                        Operations</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="font-weight-bold">Jabatan <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" name="jabatan"
                                                                    value="{{ $user->jabatan }}" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="font-weight-bold">Tanggal Masuk <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="date" class="form-control"
                                                                    name="join_date" value="{{ $user->join_date }}"
                                                                    required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="font-weight-bold">No Hp <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" name="No_HP"
                                                                    value="{{ $user->No_HP }}" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">Status <span
                                                                class="text-danger">*</span></label>
                                                        <select class="form-control" name="status" required>
                                                            <option value="active"
                                                                {{ $user->status == 'active' ? 'selected' : '' }}>Active
                                                            </option>
                                                            <option value="inactive"
                                                                {{ $user->status == 'inactive' ? 'selected' : '' }}>
                                                                Inactive</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">Role <span
                                                                class="text-danger">*</span></label>
                                                        <select class="form-control" name="role" required>
                                                            <option value="hr"
                                                                {{ $user->role == 'hr' ? 'selected' : '' }}>HR
                                                            </option>
                                                            <option value="direktur"
                                                                {{ $user->role == 'direktur' ? 'selected' : '' }}>
                                                                Direktur</option>
                                                            <option value="atasan"
                                                                {{ $user->role == 'atasan' ? 'selected' : '' }}>
                                                                Atasan</option>
                                                            <option value="karyawan"
                                                                {{ $user->role == 'karyawan' ? 'selected' : '' }}>
                                                                Karyawan</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">
                                                        <i class="fas fa-times"></i> Tutup
                                                    </button>
                                                    <button type="submit" class="btn btn-warning text-white">
                                                        <i class="fas fa-save"></i> Update
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>


                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                                        <p class="text-muted">Tidak ada data pengguna</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>


                </div>
                <div class="d-flex justify-content-center mt-3">
                    {{ $users->links() }}
                </div>

                <!-- Pagination -->
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->



    <!-- Tambah Data Modal -->
    <div class="modal fade" id="tambahDataModal" tabindex="-1" role="dialog" aria-labelledby="tambahDataModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="tambahDataModalLabel">
                        <i class="fas fa-user-plus"></i> Tambah Data Pengguna
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ route('pengguna.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">No ID <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="badge_number"
                                        placeholder="Contoh: EMP001" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Nama <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" placeholder="Nama lengkap"
                                        required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" name="email"
                                        placeholder="email@example.com" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" name="password"
                                        placeholder="Minimal 6 karakter" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Department <span class="text-danger">*</span></label>
                                    <select class="form-control" name="departement" required>
                                        <option value="">Pilih Departmen</option>
                                        <option value="Office">Office</option>
                                        <option value="Sales">Sales</option>
                                        <option value="Production">Production</option>
                                        <option value="Engineering">Engineering</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Jabatan <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="jabatan"
                                        placeholder="Contoh: Manager" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Tanggal Masuk <span
                                            class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="join_date" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">No Hp <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="No_HP" placeholder="08XXXXXXXXXX"
                                        required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Status <span class="text-danger">*</span></label>
                            <select class="form-control" name="status" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Role <span class="text-danger">*</span></label>
                            <select class="form-control" name="role" required>
                                @foreach (['hr', 'direktur', 'atasan', 'karyawan'] as $role)
                                    <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Tutup
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Form Delete (Hidden) -->

    <form id="deleteForm" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>

    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('pengguna.data.import') }}" method="POST" enctype="multipart/form-data">
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
@endsection

@push('styles')
    <style>
        .table th {
            vertical-align: middle;
            white-space: nowrap;
        }

        .table td {
            vertical-align: middle;
        }

        .btn-group .btn {
            margin: 0 !important;
        }

        .modal-header.bg-primary,
        .modal-header.bg-warning,
        .modal-header.bg-info {
            color: white !important;
        }

        .modal-header .close {
            color: white !important;
            opacity: 0.8;
        }

        .modal-header .close:hover {
            opacity: 1;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('deleteForm');
                    form.action = '/pengguna/' + id;
                    form.submit();
                }
            });
        }

        // View toggle functionality
        document.getElementById('gridViewBtn')?.addEventListener('click', function() {
            Swal.fire({
                icon: 'info',
                title: 'Grid View',
                text: 'Fitur grid view akan segera tersedia',
                timer: 2000,
                showConfirmButton: false
            });
        });
    </script>
@endpush
