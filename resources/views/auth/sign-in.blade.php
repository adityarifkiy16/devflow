@extends('layouts.auth')
@section('content')
<main class="main-content  mt-0">
  <section>
    <div class="page-header min-vh-100">
      <div class="container">
        <div class="row">
          <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column mx-lg-0 mx-auto">
            <div class="card card-plain">
              <div class="card-header pb-0 text-start">
                <h4 class="font-weight-bolder">Sign In</h4>
                <p class="mb-0">Enter your email and password to sign in</p>
              </div>
              <div class="card-body">
                <form role="form" id="login-form">
                  <div class="mb-3">
                    <input type="text" name="login" class="form-control form-control-lg" placeholder="Email or Username" aria-label="Email or Username">
                  </div>
                  <div class="mb-3">
                    <input type="password" name="password" class="form-control form-control-lg" placeholder="Password" aria-label="Password">
                  </div>
                  <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="rememberMe">
                    <label class="form-check-label" for="rememberMe">Remember me</label>
                  </div>
                  <div class="text-center">
                    <button type="submit" class="btn btn-lg btn-primary btn-lg w-100 mt-4 mb-0">Sign in</button>
                  </div>
                </form>
              </div>
              <div class="card-footer text-center pt-0 px-lg-2 px-1">
                <p class="mb-4 text-sm mx-auto">
                  Don't have an account?
                  <a href="javascript:;" class="text-primary text-gradient font-weight-bold">Sign up</a>
                </p>
              </div>
            </div>
          </div>
          <div class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 end-0 text-center justify-content-center flex-column">
            <div class="position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center overflow-hidden" style="background-image: url('/assets/img/bg-img.jpg');
          background-size: cover;">
              <span class="mask bg-gradient-dark opacity-3"></span>
              <h4 class="mt-5 text-white font-weight-bolder position-relative">"Jack Of All Trades"</h4>
              <p class="text-white position-relative">Master of None.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
@endsection
@push('script')
<script>
  $('#login-form').submit(function(event) {
    event.preventDefault();

    const Toast = Swal.mixin({
      toast: true,
      position: "top-end",
      showClass: {
        popup: `
                animate__animated
                animate__fadeInDown
                animate__faster
                `
      },
      showConfirmButton: false,
      timer: 1500,
      timerProgressBar: true,
      didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
      }
    });

    // Get form data
    let formData = $(this).serialize();

    let token = $('meta[name="csrf-token"]').attr('content');

    // Send AJAX request
    $.ajax({
      url: "/login",
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
  });
</script>
@endpush

</html>