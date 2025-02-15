@extends('layouts.index', ['title' => $project->name, 'page' => 'Project', 'subpage' => 'Kanban Task'])
@section('content')
<div class="container-fluid mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="text-white">{{ $project->name }} - Kanban Task</h3>
        <form role="form text-left" class="project-delete" id="project-delete">
            <input type="hidden" name="project_id" id="delete-project-id" value="{{ $project->id }}">
            <button type="submit" class="btn bg-danger btn-lg btn-rounded w-100 mb-0 text-white">Delete</button>
        </form>
    </div>
    <div class="row overflow-hidden">
        @foreach($statuses as $status)
        <div class="col-md-3 mt-3 p-1">
            <div class="card shadow-sm">
                <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                    {{ $status->title }}
                    <button class="btn text-white bg-gradient-warning mb-0 me-1 d-flex align-items-center p-2" data-bs-toggle='modal' data-bs-target='#modal-tambah-{{$status->id}}'><i class="ni ni-fat-add text-white"></i></button>
                </div>
                <div class="card-body sortable px-2 overflow-auto" style="max-height: 400px;" data-status-id="{{ $status->id }}">
                    @foreach ($project->tasks->where('status_id', $status->id)->sortBy('order') as $task)
                    <div class="card mt-2 kanban-task" data-task-id="{{ $task->id }}">
                        <div class="card-body px-3 py-2">
                            <h5 class="card-title">{{ $task->title }}</h5>
                            <div class="d-flex justify-content-between mt-1">
                                <p class="card-text text-xs"><i class="fa fa-clock fa-xs me-1"></i>
                                    {{ \Carbon\Carbon::parse($task->update_at)->isToday() ? 'Today' : \Carbon\Carbon::parse($task->update_at)->format('Y-m-d') }}
                                </p>
                            </div>
                            <a href="#" class="stretched-link" data-bs-toggle="modal" data-bs-target="#modal-edit" data-task-id="{{ $task->id }}"></a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach
        <!-- Modal Edit/Detail Task -->
        <div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="modal-edit-Label" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-body p-0">
                        <div class="card card-plain">
                            <div class="card-header pb-0 text-left">
                                <h3 class="font-weight-bolder text-primary text-gradient" id="modal-title">Edit Task</h3>
                            </div>
                            <div class="card-body pb-3">
                                <form id="form-edit">
                                    <div id="modal-body-content"></div>
                                </form>
                                <form id="form-delete">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal Edit/Detail Task -->
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
                            <form role="form text-left" class="form-tambah" id="form-tambah-{{$status->id}}" enctype="multipart/form-data">
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
                                        <div class="input-group mb-3">
                                            <input type="file" name="files[]" class="form-control" multiple>
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

        $('.form-tambah').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            let token = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: "{{ url('/task/store') }}",
                type: 'POST',
                cache: false,
                contentType: false,
                processData: false,
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

        $('#modal-edit').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var taskId = button.data('task-id');
            var modal = $(this);

            // Clear previous content
            modal.find('#modal-body-content').html('');
            modal.find('#form-delete').html('');

            // Fetch task data and append to modal body
            $.ajax({
                url: `/task/edit/${taskId}`,
                method: 'GET',
                success: function(response) {
                    if (response.code === 200) {
                        let task = response.task;
                        let statuses = response.statuses;
                        let files = response.file;
                        let fileDisplay = files.map(function(file) {
                            let fileName = file.path.split('/').pop();
                            let fileUrl = `/storage/${file.path.replace('public/', '')}`;
                            return `<div>
                                        <img src="${fileUrl}" alt="${fileName}" style="max-width: 200px; max-height: 200px;">
                                    </div>`;
                        }).join('');

                        let statusOptions = statuses.map(function(status) {
                            let selected = '';
                            if (task.status_id == status.id) {
                                selected = 'selected'
                            }
                            return `<option value="${status.id}" ${selected}>${status.title}</option>`;
                        }).join('');
                        var content = `
                            <input type="hidden" name='task_id' value="${task.id}">
                            <label>Nama Task</label>
                            <div class="input-group mb-3">
                                <input type="text" name="title" value="${task.title}" class="form-control" placeholder="Task Name" aria-label="Name" aria-describedby="name-addon">
                            </div>
                             <label>Status</label>
                            <div class="input-group mb-3">
                               <select name="status_id" class="form-control">
                                  ${statusOptions}
                                </select>
                            </div>
                            <label>Description</label>
                            <div class="input-group mb-3">
                                <textarea name="description" class="form-control" placeholder="Description..." aria-label="Name" aria-describedby="name-addon">${task.description || ''}</textarea>
                            </div>
                            <label>File Gambar</label>
                            <div class="input-group mb-3">
                                <input type="file" name="files[]" class="form-control" multiple>
                            </div>
                                ${fileDisplay}
                            <div class="text-center">
                                <button type="submit" class="btn bg-gradient-primary btn-lg btn-rounded w-100 mt-4 mb-0">Update</button>
                            </div>
                        `;

                        let buttondelete = `
                         <input type="hidden" name="task_id" id="delete-task-id" value="${task.id}">
                         <button type="submit" class="btn bg-gradient-danger btn-lg btn-rounded w-100 mt-2 mb-0">Delete</button>`;

                        modal.find('#modal-body-content').append(content);
                        modal.find('#form-delete').append(buttondelete);
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

            modal.find('#delete-task-id').val(taskId);
        });

        $('#form-edit').submit(function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            let token = $('meta[name="csrf-token"]').attr('content');
            let taskId = $(this).find('input[name="task_id"]').val();

            $.ajax({
                url: `/task/update/${taskId}`,
                type: 'POST',
                cache: false,
                contentType: false,
                processData: false,
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

        $('#form-delete').submit(function(e) {
            e.preventDefault();
            console.log('click delete');
            let token = $('meta[name="csrf-token"]').attr('content');
            let taskId = $('#delete-task-id').val();
            $.ajax({
                url: `/task/delete/${taskId}`,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'X-HTTP-Method-Override': 'DELETE'
                },
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

        $('#project-delete').submit(function(e) {
            e.preventDefault();
            let token = $('meta[name="csrf-token"]').attr('content');
            let projectId = $('#delete-project-id').val();
            console.log(projectId);
            $.ajax({
                url: `/project/${projectId}`,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'X-HTTP-Method-Override': 'DELETE'
                },
                success: function(response) {
                    if (response.code === 200) {
                        Toast.fire({
                            icon: "success",
                            title: response.message
                        }).then(function() {
                            window.location.href = '/project';
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
@endpush