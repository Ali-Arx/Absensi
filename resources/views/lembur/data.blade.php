@extends('layouts.admin')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Data Lembur</h1>
        <a href="{{ route('lembur.data') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Edit Lembur Karyawan</h6>
        </div>
        <div class="card-body">
            <form action="" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="user_id">Karyawan <span class="text-danger">*</span></label>
                            <select class="form-control @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                                <option value="">-- Pilih Karyawan --</option>
                                @foreach($users ?? [] as $user)
                                    <option value="{{ $user->id }}" {{ (old('user_id', $lemburs->user_id) == $user->id) ? 'selected' : '' }}>
                                        {{ $user->name }} - {{ $user->badge_number ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="date">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('date') is-invalid @enderror" 
                                   id="date" name="date" value="{{ old('date', $lemburs->date->format('Y-m-d')) }}" required>
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="department">Department <span class="text-danger">*</span></label>
                            <select class="form-control @error('department') is-invalid @enderror" id="department" name="department" required>
                                <option value="">-- Pilih Department --</option>
                                <option value="HR" {{ old('department', $lemburs->department) == 'HR' ? 'selected' : '' }}>HR</option>
                                <option value="IT" {{ old('department', $lemburs->department) == 'IT' ? 'selected' : '' }}>IT</option>
                                <option value="Finance" {{ old('department', $lemburs->department) == 'Finance' ? 'selected' : '' }}>Finance</option>
                                <option value="Marketing" {{ old('department', $lemburs->department) == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                                <option value="Operations" {{ old('department', $lemburs->department) == 'Operations' ? 'selected' : '' }}>Operations</option>
                            </select>
                            @error('department')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Waktu Plan</label>
                            <div class="row">
                                <div class="col-6">
                                    <input type="time" class="form-control @error('plan_start') is-invalid @enderror" 
                                           name="plan_start" value="{{ old('plan_start', $lemburs->plan_start) }}" placeholder="Mulai">
                                    @error('plan_start')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-6">
                                    <input type="time" class="form-control @error('plan_end') is-invalid @enderror" 
                                           name="plan_end" value="{{ old('plan_end', $lemburs->plan_end) }}" placeholder="Selesai">
                                    @error('plan_end')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Waktu Actual</label>
                            <div class="row">
                                <div class="col-6">
                                    <input type="time" class="form-control @error('actual_start') is-invalid @enderror" 
                                           name="actual_start" value="{{ old('actual_start', $lemburs->actual_start) }}" placeholder="Mulai">
                                    @error('actual_start')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-6">
                                    <input type="time" class="form-control @error('actual_end') is-invalid @enderror" 
                                           name="actual_end" value="{{ old('actual_end', $lemburs->actual_end) }}" placeholder="Selesai">
                                    @error('actual_end')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="pending" {{ old('status', $lemburs->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ old('status', $lemburs->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ old('status', $lemburs->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="paraf_hours">Paraf Hours</label>
                            <input type="number" step="0.01" class="form-control" id="paraf_hours" 
                                   name="paraf_hours" value="{{ old('paraf_hours', $lemburs->paraf_hours) }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="ot_hours">OT Hours</label>
                            <input type="number" step="0.01" class="form-control" id="ot_hours" 
                                   name="ot_hours" value="{{ old('ot_hours', $lemburs->ot_hours) }}">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="job_description">Job Description <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('job_description') is-invalid @enderror" 
                              id="job_description" name="job_description" rows="4" required>{{ old('job_description', $lemburs->job_description) }}</textarea>
                    @error('job_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="notes">Catatan</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes', $lemburs->notes) }}</textarea>
                </div>

                <hr>

                <div class="text-right">
                    <a href="{{ route('lembur.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
<!-- /.container-fluid -->
@endsection