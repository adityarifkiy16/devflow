<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Nucleo Icons -->
    <link rel="stylesheet" href="{{ asset('assets/css/nucleo-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/nucleo-svg.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/icons/fontawesome/styles.min.css') }}">

    <!-- Global Styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/argon-dashboard.css?v=2.0.4') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/scss/app.scss', 'resource/css/app.css'])
</head>

<body class="g-sidenav-show bg-gray-100">
    <div class="min-height-300 bg-gradient-warning position-absolute w-100"></div>
    <x-sidebar />
    <main class="main-content position-relative border-radius-lg ">
        <x-navbar-dashboard :page="$page" :subpage="$subpage" :title="$title" />
        @yield('content')
        <x-footer />
    </main>



    <!-- Core JS -->
    <script src="{{ asset('assets/js/main/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/main/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/main/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/chartjs.min.js') }}"></script>
    <script src="{{ asset('assets/js/argon-dashboard.min.js?v=2.0.4') }}"></script>

    <!-- logout JS -->
    <script>
        $("#logout-form").submit((e) => {
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

            // Get form data
            let formData = $(this).serialize();

            // Send AJAX request
            $.ajax({
                url: "{{ url('/logout') }}",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: formData,
                success: function(response) {
                    if (response.code === 200) {
                        Toast.fire({
                            icon: "success",
                            title: response.message
                        }).then(function() {
                            window.location = '{{url("/login")}}'
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

    @stack('script')
</body>

</html>