@extends('layouts.index', ['title' => 'Master Project', 'page' => 'Project', 'subpage' => 'Project Management'])
@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-3 d-flex justify-content-between align-items-center">
                    <h3 class="mb-4">Daftar Proyek</h3>
                    <button class="btn text-white bg-primary mb-0 me-1 d-flex align-items-center" data-bs-toggle='modal' data-bs-target='#modal-tambah'><i class="ni ni-fat-add text-white me-1"></i>Tambah</button>
                </div>
                <div class="card-body px-0   pb-0">
                    <div class="list-group mt-2" style="border-radius: 0 0 15px 15px;">
                        @if($projects->isEmpty())
                        <p class="text-center fs-5">Tidak ada project</p>
                        @else
                        @foreach($projects as $project)
                        <a href="{{ route('project.kanban', $project->id) }}" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">{{ $project->name }}</h5>
                            </div>
                            <small class="text-muted">{{ $project->user->name }}</small>
                        </a>
                        @endforeach
                        <div class="d-flex justify-content-center mt-3">
                            {{ $projects->links('vendor.pagination.default') }}
                        </div>
                        @endif
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
                            <h3 class="font-weight-bolder text-primary text-gradient">Tambah Project</h3>
                        </div>
                        <div class="card-body pb-3">
                            <form role="form text-left" id="form-tambah">
                                <div class="row">
                                    <div class="col">
                                        <label>Name</label>
                                        <div class="input-group mb-3">
                                            <input type="text" name="name" class="form-control" placeholder="Project Name" aria-label="Name" aria-describedby="name-addon">
                                        </div>
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

        let formData = $(this).serialize(); // Perbaikan di sini
        let token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: "{{ route('project.store') }}",
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
                        window.location = '{{ route("project.index") }}';
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