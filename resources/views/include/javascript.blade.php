<script>
    $(document).ready(function(){
        @if(session('success'))
        toastr.success('{{ session('success') }}','',{
                "positionClass": "toast-top-center",
            });
         @endif
         @if($errors->any())
                @foreach($errors->all() as $error)
                    toastr.error('{{ $error }}','',{
                "positionClass": "toast-top-center",
            });
                @endforeach
         @endif
         var project_id = $.cookie('project_id');

            if (project_id != 0 && project_id != undefined) {
                $('.project_id').val(project_id);
                getTaskList(project_id);
            }else{
                project_id = 0;
            }
        var tasklist = document.getElementById('tasklist');
            new Sortable(tasklist, {
                animation: 150,
                ghostClass: 'blue-background-class'

            });
        $(".save_sort").hide();
        $(".project_id").change(function(){
            var project_id = $(this).val();
            console.log(project_id);
             $.cookie('project_id', project_id, {path: '/'});
            if(project_id == "" || project_id == null){
                var taskList = $("#tasklist");
                taskList.empty();
                taskList.append("<tr><td colspan='5' class='text-center'>Please Select Project</td></tr>");
                return false;
            }else{
                getTaskList(project_id);
            }
        });

        // on click create_task_btn buton
        $('.create_task_btn').click(function(){
            var project_id = $('.project_id').val();
            if(project_id == "" || project_id == null){
                toastr.error('Please Select Project','',{
                "positionClass": "toast-top-center",
            });
                return false;
            }else{
                $('#task_project_id').val(project_id);
                $('#create_task').modal('show');
            }

        })

        function getTaskList(project_id){
            $.ajax({
                url:"{{route('project.task.list')}}",
                method:"POST",
                data:{
                    "_token" : "{{csrf_token()}}",
                    project_id : project_id,
                },
                success:function(response){
                    if(response.tasks != null && response.tasks != ""){
                        var taskList = $("#tasklist");
                        taskList.empty();
                        taskList.append(response.tasks);
                        $(".save_sort").show();
                    }
                }
            });
        }

            // on click save_sort button
            $('.save_sort').click(function() {
                var sort=[];
                $(".task_item").each(function(i) {
                    var id = $(this).attr('data-task_id');
                    sort.push(parseInt(id))
                });
                console.log(sort);
                // check if sort array is empty
                if(sort.length > 0){
                    var project_id = $('.project_id').val();
                    if(project_id == "" || project_id == null){

                        var taskList = $("#tasklist");
                        taskList.empty();
                        taskList.append("<tr><td colspan='5' class='text-center'>Please Select Project</td></tr>");
                        return false;
                    }
                    $.ajax({
                        url:"{{route('task.sort')}}",
                        method:"POST",
                        data:{
                            "_token" : "{{csrf_token()}}",
                            project_id : project_id,
                            sort : sort,
                        },
                        success: function(response) {
                            toastr.success('Your current changes are Saved','',{
                                "positionClass": "toast-top-center",
                            });
                        },
                        error: function(error) {
                            console.log(error);
                        }
                    });
                }
            });

    });

</script>
