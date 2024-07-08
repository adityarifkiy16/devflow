@extends('layouts.index', ['title' => 'Change Profile', 'page' => 'Edit', 'subpage' => 'User'])
@section('content')
<div class="container">
    <div class="card">
        <div class="card-header pb-0 text-start">
            <h4 class="font-weight-bolder">Change Profile</h4>
        </div>
        <div class="card-body">
            <form action="/users/{{ $user->id }}" method="POST" role="form" id="form-edit">
                <input type="hidden" name="user_id" value="{{ $user->id }}" />
                <label>Name</label>
                <div class="input-group mb-3">
                    <input type="text" name='name' class="form-control" placeholder="Name" aria-label="Name" aria-describedby="name-addon" value="{{ $user->name }}">
                </div>
                <label>Email</label>
                <div class="input-group mb-3">
                    <input type="email" class="form-control" name='email' placeholder="Email" aria-label="Email" aria-describedby="email-addon" value="{{ $user->email }}">
                </div>
                <!-- New Password (optional) -->
                <label>New Password (optional)</label>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" name="password" placeholder="New Password" aria-label="New Password" aria-describedby="password-addon" id="new-password">
                    <button type="button" class="btn btn-outline-secondary toggle-password mb-0" data-toggle="#new-password">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>

                <!-- Confirm New Password (optional) -->
                <label>Confirm New Password (optional)</label>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm New Password" aria-label="Confirm New Password" aria-describedby="password-confirmation-addon" id="confirm-password">
                    <button type="button" class="btn btn-outline-secondary toggle-password mb-0" data-toggle="#confirm-password">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>

                <div class="d-flex flex-row justify-content-center align-items-center">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('script')
<script>
    $(document).ready(function() {
        $('.toggle-password').click(function() {
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
    $('#form-edit').submit(function(e) {
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
</script>
@endpush