<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Task Management</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/js/bootstrap.min.js') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body>


    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container">
                <div class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">

                    <div class="nav-item dropdown">
                        <select class="form-select text-center project_id">
                            @if ($projects->count() == 0)
                            <option value="">Please Create a Project First</option>
                            @else
                            <option value="">select Project From Drop Down</option>
                            @endif
                            @foreach ($projects as $project)

                            <option value="{{$project->id}}">{{$project->name}}</option>
                            @endforeach
                          </select>
                    </div>
                </div>
                <div class="d-flex" role="search">
                    <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#create_project">Create Project</button>
                    <button type="button" class="btn btn-primary create_task_btn">Create Task</button>
                </div>
            </div>
        </nav>
        <!-- table -->
        <section class="container mt-4">
            <div class="d-flex justify-content-between">
                <h2>Task List</h2>
                <button type="button" class="btn btn-primary mb-3 save_sort">Save Ordering</button>
            </div>
            <table class="table table-responsive table-hover table-bordered text-center">
                <thead>
                    <tr class="item">
                        <th>Name</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="tasklist" >
                    @if ($projects->count() == 0)
                            <tr><td colspan='5' class='text-center'>Please Create a Project First</td></tr>
                    @else
                            <tr><td colspan='5' class='text-center'>Please Select Project</td></tr>
                    @endif
                </tbody>
            </table>
        </section>

    </div>
    <!-- Button trigger modal -->


  <!-- create Project Modal -->
  <div class="modal fade" id="create_project" tabindex="-1" aria-labelledby="create_projectLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="create_projectLabel">Create a new Project</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
           <form method="post" action="{{route('project.create')}}">
             @csrf
             <div class="mb-3">
                <label for="project name" class="form-label">Project Name</label>
                <input type="text" class="form-control" id="project name" name="name"  aria-describedby="emailHelp" required>
                <div id="emailHelp" class="form-text">A new project will be created</div>
              </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Create</button>
        </div>
        </form>
      </div>
    </div>
  </div>

    <!-- create task Modal -->
    <div class="modal fade" id="create_task" tabindex="-1" aria-labelledby="create_taskLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="create_taskLabel">Create a new task</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               <form method="post" action="{{route('task.create')}}">
                 @csrf
                 <div class="mb-3">
                    <label for="task name" class="form-label">Task Name</label>
                    <input type="text" class="form-control" id="task name" name="name"  aria-describedby="emailHelp" required>
                    <div id="emailHelp" class="form-text">A new task will be created Under Currently Selected Project</div>
                  </div>
                  <div class="mb-3">
                    <label for="task status" class="form-label">Task Status</label>
                    <input type="text" class="form-control" id="task status" name="status"  aria-describedby="emailHelp" required>
                    <div id="emailHelp" class="form-text">Give it a Status</div>
                  </div>
                  <input type="hidden" name="project_id" id="task_project_id" >
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Create</button>
            </div>
            </form>
          </div>
        </div>
      </div>

    <!-- Update task Modal -->
    <div class="modal fade" id="update_task" tabindex="-1" aria-labelledby="Update_taskLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="Update_taskLabel">Update <span class="name_of_task"> </span></h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               <form method="post" action="{{route('task.update')}}">
                 @csrf
                 <div class="mb-3">
                    <label for="task name" class="form-label">Task Name</label>
                    <input type="text" class="form-control" id="task_name_update" name="name"  aria-describedby="emailHelp" required>
                  </div>
                  <div class="mb-3">
                    <label for="task status" class="form-label">Task Status</label>
                    <input type="text" class="form-control" id="task_status_update" name="status"  aria-describedby="emailHelp" required>
                  </div>
                  <input type="hidden" name="project_id" id="edit_task_project_id" >
                  <input type="hidden" name="task_id" id="task_id" >
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Update</button>
            </div>
            </form>
          </div>
        </div>
      </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="{{ asset('assets/sortTable/Sortable.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{asset('assets/')}}/js/jquery.cookie.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
   @include('include.javascript')
</body>

</html>
