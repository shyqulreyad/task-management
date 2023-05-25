    @forelse ($tasks as $task)
    <tr>
        <td class="task_item" data-task_id="{{$task->id}}">{{$task->name}}</td>
        <td>{{$task->status}}</td>
        <td>
            <?php
            if(isset($task->created_at)){
                $createdat = \Carbon\Carbon::parse($task->created_at)->format('Y-m-d H:i:A');
             }else{
                 $createdat = 'N/A';
             }
             ?>

             {{$createdat}}
        </td>
        <td>
            <?php
            if(isset($task->updated_at)){
                $updatedat = \Carbon\Carbon::parse($task->updated_at)->format('Y-m-d H:i:A');
                if($createdat == $updatedat){
                    $updatedat = 'N/A';
                }
             }else{
                if($createdat == $updatedat){
                    $updatedat = 'N/A';
                }
             }
             ?>

             {{$updatedat}}
        </td>
        <td class="text-center">
            <a href="#" class="btn btn-primary btn-sm edit_task_btn" data-task_id="{{$task->id}}" data-project_id="{{$task->project_id}}" data-name="{{$task->name}}" data-status="{{$task->status}}">Edit</a>
            <button class="btn btn-danger btn-sm delete_task_btn" data-task_id="{{$task->id}}" data-project_id="{{$task->project_id}}">Delete</button>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="5" class="text-center">No Task Found</td>
    </tr>
    @endforelse
    <script>
                // on click delete_task button ajax request
            $('.delete_task_btn').click(function(e) {
                e.preventDefault();
                var task_id = $(this).attr('data-task_id');
                var project_id = $(this).attr('data-project_id');
                $.ajax({
                    url:"{{route('task.delete')}}",
                    method:"POST",
                    data:{
                        "_token" : "{{csrf_token()}}",
                        task_id : task_id,
                        project_id : project_id,
                    },
                    success: function(response) {
                        if(response.status == 'Success'){
                            toastr.success('Task deleted Successfully','',{
                                "positionClass": "toast-top-center",
                            });
                            getTaskList(project_id);
                        }
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });

            });

            // on click edit_task button show modal
            $('.edit_task_btn').click(function(e) {
                e.preventDefault();
                var task_id = $(this).attr('data-task_id');
                var project_id = $(this).attr('data-project_id');
                var name = $(this).attr('data-name');
                var status = $(this).attr('data-status');
                $('#task_id').val(task_id);
                $('#edit_task_project_id').val(project_id);
                $('#task_name_update').val(name);
                $('.name_of_task').text(name);
                $('#task_status_update').val(status);
                $('#update_task').modal('show');
            });

            function getTaskList(project_id){
                $.ajax({
                    url:"{{route('project.task.list')}}",
                    method:"POST",
                    data:{
                        "_token" : "{{csrf_token()}}",
                        project_id : project_id,
                    },
                    success:function(response){
                        if(response.tasks){
                            var taskList = $("#tasklist");
                            taskList.empty();
                            taskList.append(response.tasks);
                            $(".save_sort").show();
                        }
                    }
                });
            }

    </script>
