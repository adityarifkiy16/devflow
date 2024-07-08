@extends('layouts.index', ['title' => 'Master User', 'page' => 'Pages', 'subpage' => 'User'])
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-3 d-flex justify-content-between">
                    <h5>Users table</h5>
                    @if(auth()->check() && in_array(auth()->user()->role_id, [1,2,3]))
                    <button class="btn text-white bg-primary mb-0 me-1 py-1 px-2 d-flex align-items-center" data-bs-toggle='modal' data-bs-target='#modal-tambah'><i class="ni ni-fat-add text-white me-1"></i>Tambah</button>
                    @endif
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
                                                <button class="btn bg-gradient-primary mb-0 me-1" data-bs-toggle="modal" data-bs-target="#modal-edit" data-user-id="{{$user->id}}"><i class="fa fa-pen text-white"></i></button>
                                                <!-- Form Delete Task -->
                                                <form role="form" class="form-delete" id="form-delete-{{ $user->id }}">
                                                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                                                    <button class="btn bg-gradient-danger mb-0" type="submit"><i class="fa fa-trash text-white"></i></button>
                                                </form>
                                                <!-- End Form Delete Task -->
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
                                        <input type="password" name="password" class="form-control" placeholder="Password" aria-label="Password" aria-describedby="password-addon" id="new-password">
                                        <button type="button" class="btn btn-outline-secondary toggle-password mb-0" data-toggle="#new-password">
                                            <i class="fas fa-eye"></i>
                                        </button>
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
<div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalSignTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="card card-plain">
                    <div class="card-header pb-0 text-left">
                        <h3 class="font-weight-bolder text-primary text-gradient">Edit User</h3>
                        <p class="mb-0">Masukan data user</p>
                    </div>
                    <div class="card-body pb-3">
                        <form role="form text-left" class="form-edit">
                            <div id="modal-body-content"></div>
                            <div class="text-center">
                                <button type="submit" class="btn bg-gradient-primary btn-lg btn-rounded w-100 mt-4 mb-0">Update</button>
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
    $(document).ready(function() {
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

        $('#form-tambah').submit(function(e) {
            e.preventDefault();
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

        $('.form-delete').submit(function(e) {
            e.preventDefault();
            let token = $('meta[name="csrf-token"]').attr('content');
            let form = $(this);
            let userId = form.find("input[name='user_id']").val();

            $.ajax({
                url: `/users/${userId}`,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'X-HTTP-Method-Override': 'DELETE'
                },
                success: function(res) {
                    Toast.fire({
                        icon: "success",
                        title: res.message
                    }).then(function() {
                        window.location = '/users'
                    });
                },
                error: function(xhr) {
                    let errorMessage = xhr.responseJSON.message ? xhr.responseJSON.message : 'An error occurred';
                    Toast.fire({
                        icon: "error",
                        title: errorMessage
                    });
                }
            });
        });

        $('#modal-edit').on('show.bs.modal', function(e) {
            let button = $(e.relatedTarget);
            let userId = button.data('user-id');
            let modal = $(this);
            modal.find('#modal-body-content').html('');
            $.ajax({
                url: `/users/${userId}/edit?include_roles=true`,
                type: 'GET',
                success: function(res) {
                    if (res.code === 200) {
                        let user = res.data.user;
                        console.log(user);
                        let roles = res.data.roles;
                        let rolesOption = roles.map(function(role) {
                            let selected = '';
                            if (user.role_id == role.id) {
                                selected = 'selected';
                            }
                            return `<option value=${role.id} ${selected}>${role.name}</option>`
                        }).join('');
                        let content = `
                        <input type="hidden" name="user_id" value=${user.id}/>
                        <label>Name</label>
                                <div class="input-group mb-3">
                                    <input type="text" name='name' class="form-control" placeholder="Name" aria-label="Name" aria-describedby="name-addon" value=${user.name}>
                                </div>
                                <label>Email</label>
                                <div class="input-group mb-3">
                                    <input type="email" class="form-control" name='email' placeholder="Email" aria-label="Email" aria-describedby="email-addon" value=${user.email}>
                                </div>
                                <label>New Password (optional)</label>
                                <div class="input-group mb-3">
                                    <input type="password" class="form-control" id='edit-password' name='password' placeholder="Password" aria-label="Password" aria-describedby="password-addon">
                                    <button type="button" class="btn btn-outline-secondary toggle-password mb-0" data-toggle="#edit-password">
                                            <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <label>Confirm New Password (optional)</label>
                                <div class="input-group mb-3">
                                    <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm New Password" aria-label="Confirm New Password" aria-describedby="password-confirmation-addon" id="confirm-password">
                                    <button type="button" class="btn btn-outline-secondary toggle-password mb-0" data-toggle="#confirm-password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>  
                                <div class="form-group">
                                    <label for="role">Role</label>
                                    <select class="form-control form-select" name='role'>
                    ${rolesOption}
                                    </select>
                                </div>
                        `
                        modal.find('#modal-body-content').append(content);
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: 'Unable to fetch task data'
                        });
                    }
                },
                error: function(xhr) {
                    let errorMessage = xhr.responseJSON.message ? xhr.responseJSON.message : 'An error occurred';
                    Swal.fire({
                        icon: "error",
                        title: errorMessage
                    });
                }
            });
        });

        $('.form-edit').submit(function(e) {
            e.preventDefault();
            let formData = $(this).serialize();
            let token = $('meta[name="csrf-token"]').attr('content');
            let userId = $(this).find('input[name="user_id"]').val();

            $.ajax({
                url: `/users/${userId}`,
                type: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': token,
                },
                data: formData,
                success: function(response) {
                    if (response.code === 200) {
                        Toast.fire({
                            icon: "success",
                            title: response.message
                        }).then(function() {
                            window.location.reload();
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
        });

        $(document).on('click', '.toggle-password', function() {
            let input = $($(this).data('toggle'));
            let icon = $(this).find('i');

            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                input.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });
    });
</script>
@endpush