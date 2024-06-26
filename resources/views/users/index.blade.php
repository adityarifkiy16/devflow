@extends('layouts.index', ['title' => 'Master User', 'page' => 'Pages', 'subpage' => 'User'])
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-3 d-flex justify-content-between">
                    <h5>Users table</h5>
                    <button class="btn text-white bg-primary mb-0 me-1 py-1 px-2 d-flex align-items-center" data-bs-toggle='modal' data-bs-target='#modal-tambah'><i class="ni ni-fat-add text-white me-1"></i>Tambah</button>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Username</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Role</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $user->name }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $user->username }}</p>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="badge badge-sm bg-gradient-danger">{{ $user->role ? $user->role->name : 'Tidak ada role' }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center px-2 py-1">
                                            <div class="d-flex flex-row justify-content-center align-items-center">
                                                <button class="btn bg-gradient-success mb-0 me-1"><i class="fa fa-info text-white"></i></button>
                                                <button class="btn bg-gradient-primary mb-0 me-1" data-bs-toggle="modal" data-bs-target="#modal-edit"><i class="fa fa-pen text-white"></i></button>
                                                <button class="btn bg-gradient-danger mb-0"><i class="fa fa-trash text-white"></i></button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal tambah -->
<div class="modal fade" id="modal-tambah" tabindex="-1" role="dialog" aria-labelledby="exampleModalSignTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="card card-plain">
                    <div class="card-header pb-0 text-left">
                        <h3 class="font-weight-bolder text-primary text-gradient">Tambah User</h3>
                        <p class="mb-0">Masukan data user</p>
                    </div>
                    <div class="card-body pb-3">
                        <form role="form text-left" id="form-tambah">
                            <div class="row">
                                <div class="col">
                                    <label>Name</label>
                                    <div class="input-group mb-3">
                                        <input type="text" name="name" class="form-control" placeholder="Name" aria-label="Name" aria-describedby="name-addon">
                                    </div>
                                    <label>Username</label>
                                    <div class="input-group mb-3">
                                        <input type="text" name="username" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="name-addon">
                                    </div>
                                    <label>Nomer Telepon</label>
                                    <div class="input-group mb-3">
                                        <input type="text" name="nomer_telp" class="form-control" placeholder="08xxxxx" aria-label="telp" aria-describedby="name-addon">
                                    </div>
                                    <label>Tempat Lahir</label>
                                    <div class="input-group mb-3">
                                        <input type="text" name="tempat_lahir" class="form-control" placeholder="Tempat Lahir" aria-label="Tempat Lahir" aria-describedby="name-addon">
                                    </div>
                                    <div class="form-group">
                                        <label for="example-date-input" class="form-control-label">Tanggal Lahir</label>
                                        <input class="form-control" name="tanggal_lahir" type="date" value="{{ old('tanggal_lahir', date('Y-m-d')) }}" id="example-date-input">
                                    </div>
                                </div>
                                <div class="col">
                                    <label>Alamat</label>
                                    <div class="input-group mb-3">
                                        <input type="text" name="alamat" class="form-control" placeholder="Alamat" aria-label="Alamat" aria-describedby="name-addon">
                                    </div>
                                    <label>Email</label>
                                    <div class="input-group mb-3">
                                        <input type="email" name="email" class="form-control" placeholder="Email" aria-label="Email" aria-describedby="email-addon">
                                    </div>
                                    <label>Password</label>
                                    <div class="input-group mb-3">
                                        <input type="password" name="password" class="form-control" placeholder="Password" aria-label="Password" aria-describedby="password-addon">
                                    </div>
                                    <label>Role</label>
                                    <select class="form-select" name="role" required>
                                        <option selected>{{ __('Pilih Role') }}</option>
                                        @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ old('role') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn bg-gradient-primary btn-lg btn-rounded w-100 mt-4 mb-0">Tambah</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal edit -->
<div class="modal fade" id="form-edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalSignTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="card card-plain">
                    <div class="card-header pb-0 text-left">
                        <h3 class="font-weight-bolder text-primary text-gradient">Edit User</h3>
                        <p class="mb-0">Masukan data user</p>
                    </div>
                    <div class="card-body pb-3">
                        <form role="form text-left">
                            @csrf
                            @method('PUT')
                            <label>Name</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Name" aria-label="Name" aria-describedby="name-addon">
                            </div>
                            <label>Email</label>
                            <div class="input-group mb-3">
                                <input type="email" class="form-control" placeholder="Email" aria-label="Email" aria-describedby="email-addon">
                            </div>
                            <label>Password</label>
                            <div class="input-group mb-3">
                                <input type="password" class="form-control" placeholder="Password" aria-label="Password" aria-describedby="password-addon">
                            </div>
                            <div class="form-group">
                                <label for="role">Role</label>
                                <select class="form-control">
                                    <option selected value="{{$user->role_id}}">{{$user->role->name}}</option>
                                    @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="text-center">
                                <button type="button" class="btn bg-gradient-primary btn-lg btn-rounded w-100 mt-4 mb-0">Tambah</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
<script>
    $('#form-tambah').submit(function(e) {
        e.preventDefault();

        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });
        let formData = $(this).serialize();
        console.log(formData);
        let token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: "{{ route('users.store') }}",
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': token
            },
            data: formData,
            success: function(response) {
                if (response.code === 200) {
                    Toast.fire({
                        icon: "success",
                        title: response.message
                    }).then(function() {
                        window.location = '{{route("admin.dashboard")}}'
                    });
                } else {
                    Toast.fire({
                        icon: "error",
                        title: 'Unexpected Errors'
                    });
                }
            },
            error: function(xhr) {
                let errorMessage = xhr.responseJSON.message ? xhr.responseJSON.message : 'An error occurred';
                Toast.fire({
                    icon: "error",
                    title: errorMessage
                });
            }
        });
    })
</script>
@endpush