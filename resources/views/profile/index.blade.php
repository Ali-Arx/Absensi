@extends('layouts.app')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">My Profile</h1>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <!-- Profile Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">1. Informasi Akun</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group row">
                            <label for="no_karyawan" class="col-sm-3 col-form-label">No Karyawan</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control @error('no_karyawan') is-invalid @enderror" 
                                       id="no_karyawan" name="no_karyawan" value="{{ old('no_karyawan', $user->no_karyawan) }}" required>
                                @error('no_karyawan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="name" class="col-sm-3 col-form-label">Nama Lengkap</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-sm-3 col-form-label">Email</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="no_hp" class="col-sm-3 col-form-label">No HP</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control @error('no_hp') is-invalid @enderror" 
                                       id="no_hp" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}" required>
                                @error('no_hp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="alamat" class="col-sm-3 col-form-label">Alamat</label>
                            <div class="col-sm-9">
                                <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                          id="alamat" name="alamat" rows="3" required>{{ old('alamat', $user->alamat) }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="jabatan" class="col-sm-3 col-form-label">Jabatan</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control @error('jabatan') is-invalid @enderror" 
                                       id="jabatan" name="jabatan" value="{{ old('jabatan', $user->jabatan) }}" required>
                                @error('jabatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="department" class="col-sm-3 col-form-label">Department</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control @error('department') is-invalid @enderror" 
                                       id="department" name="department" value="{{ old('department', $user->department) }}" required>
                                @error('department')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="text-center">
                            <label class="font-weight-bold">Pilih Foto</label>
                            <div class="mb-3">
                                @if($user->foto)
                                    <img src="{{ asset('storage/' . $user->foto) }}" alt="Profile Photo" 
                                         class="img-thumbnail" id="preview-image" style="max-width: 250px; max-height: 250px;">
                                @else
                                    <img src="{{ asset('img/undraw_profile.svg') }}" alt="Default Profile" 
                                         class="img-thumbnail" id="preview-image" style="max-width: 250px; max-height: 250px;">
                                @endif
                            </div>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input @error('foto') is-invalid @enderror" 
                                       id="foto" name="foto" accept="image/*" onchange="previewImage(event)">
                                <label class="custom-file-label" for="foto">Pilih File</label>
                                @error('foto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="form-text text-muted">Tidak ada file yang dipilih</small>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="text-right">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Password Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">2. Ubah Password</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('profile.password') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password_lama">Password Lama</label>
                            <input type="password" class="form-control @error('password_lama') is-invalid @enderror" 
                                   id="password_lama" name="password_lama" required>
                            @error('password_lama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password_baru">Password Baru</label>
                            <input type="password" class="form-control @error('password_baru') is-invalid @enderror" 
                                   id="password_baru" name="password_baru" required>
                            @error('password_baru')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password_baru_confirmation">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control" 
                                   id="password_baru_confirmation" name="password_baru_confirmation" required>
                        </div>

                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-key"></i> Update Password
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

@push('scripts')
<script>
function previewImage(event) {
    const input = event.target;
    const preview = document.getElementById('preview-image');
    const label = input.nextElementSibling;
    const fileName = input.files[0].name;
    
    label.textContent = fileName;
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// Bootstrap custom file input label update
$('.custom-file-input').on('change', function() {
    let fileName = $(this).val().split('\\').pop();
    $(this).next('.custom-file-label').addClass("selected").html(fileName);
});
</script>
@endpush
@endsection