@extends('layouts.index', ['title' => $project->name, 'page' => 'Project', 'subpage' => 'Kanban Task'])
@section('content')
<div class="container-fluid my-5">
    <h3 class="mb-4 text-white">{{ $project->name }} - Kanban Task</h3>
    <div class="row">
        @foreach($statuses as $status)
        <div class="col-md-4 mt-3">
            <div class="card shadow-sm">
                <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                    {{ $status->title }}
                    <button class="btn text-white bg-gradient-warning mb-0 me-1 d-flex align-items-center p-2" data-bs-toggle='modal' data-bs-target='#modal-tambah-{{$status->id}}'><i class="ni ni-fat-add text-white"></i></button>
                </div>
                <div class="card-body sortable" data-status-id="{{ $status->id }}">
                    @foreach ($project->tasks->where('status_id', $status->id)->sortBy('order') as $task)
                    <div class="card mt-2 kanban-task" data-task-id="{{ $task->id }}">
                        <div class="card-body px-3 py-2">
                            <h5 class="card-title">{{ $task->title }}</h5>
                            <div class="d-flex justify-content-between mt-1">
                                <p class="card-text fs-6"><i class="fa fa-clock me-2"></i>{{ $task->created_at }}</p>
                            </div>
                            <a href="{{ route('project.kanban', $project->id) }}" class="stretched-link"></a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <!-- Modal tambah -->
    @foreach($statuses as $status)
    <div class="modal fade" id="modal-tambah-{{$status->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalSignTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="card card-plain">
                        <div class="card-header pb-0 text-left">
                            <h3 class="font-weight-bolder text-primary text-gradient">Tambah Task</h3>
                        </div>
                        <div class="card-body pb-3">
                            <form role="form text-left" class="form-tambah" id="form-tambah-{{$status->id}}">
                                <div class="row">
                                    <div class="col">
                                        <input type="hidden" name='status_id' value="{{ $status->id }}">
                                        <input type="hidden" name='project_id' value="{{ $project->id }}">
                                        <label>Nama Task</label>
                                        <div class="input-group mb-3">
                                            <input type="text" name="title" class="form-control" placeholder="Task Name" aria-label="Name" aria-describedby="name-addon">
                                        </div>
                                        <label>Description</label>
                                        <div class="input-group mb-3">
                                            <textarea name="description" class="form-control" placeholder="Description..." aria-label="Name" aria-describedby="name-addon"></textarea>
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
    @endforeach
</div>
@endsection

@push('script')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Initialize Dragula
        const containers = [];
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            showClass: {
                popup: `
                animate__animated
                animate__fadeInDown
                animate__faster
                `
            },
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });
        document.querySelectorAll('.sortable').forEach(function(element) {
            containers.push(element);
        });

        dragula(containers).on('drop', function(el, target, source, sibling) {
            // Get the status id and task id
            var statusId = $(target).data('status-id');
            var tasks = [];

            // Calculate the new order of tasks
            $(target).find('.kanban-task').each(function(index, element) {
                tasks.push({
                    id: $(element).data('task-id'),
                    order: index + 1 // Order starts from 1
                });
            });

            // Make AJAX call to update task status
            $.ajax({
                url: "{{ url('/task/update-task-status') }}",
                method: "POST",
                data: {
                    status_id: statusId,
                    tasks: tasks,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.code === 200) {
                        Toast.fire({
                            icon: "success",
                            title: response.message
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
    });
</script>
<script>
    $('.form-tambah').submit(function(e) {
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

        $.ajax({
            url: "{{ url('/task/store') }}",
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