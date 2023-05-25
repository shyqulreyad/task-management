<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Project;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;



class TaskController extends Controller
{

    public function create_project(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
           return back()->withErrors($validator->errors());
        }
        $project = new Project();
        $project->name = $request->name;
        $project->save();
        return back()->with('success', 'Project created successfully.');
    }

    public function create_task(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required',
            'name' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ],400);
        }
        $task = new Task();
        $task->project_id = $request->project_id;
        $task->name = $request->name;
        $task->status = $request->status;
        $task->save();
        return back()->with('success', 'Task created successfully.');
    }

    public function update_task(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'task_id' => 'required',
            'name' => 'required',
            'status' => 'required',
            'project_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ],400);
        }
        $task =  Task::where([
            'id' => $request->task_id,
            'project_id' => $request->project_id,
        ])->first();
        $task->name = $request->name;
        $task->status = $request->status;
        $task->save();
        return back()->with('success', 'Task updated successfully.');
    }

    public function delete_task(Request $request)
    {
        $task = Task::where([
            'id' => $request->task_id,
            'project_id' => $request->project_id,
        ])->first();
        $task->delete();
        return response()->json([
            'status' => 'Success',
            'message' => 'Task deleted successfully',
        ],200);
    }
    public function task_list(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ],400);
        }
        $project_id = $request->project_id;
         $sort = Project::find($project_id)->sort;
         if($sort){
             $sort = json_decode($sort);
             $tasks = Task::where('project_id', $project_id)
                        ->orderByRaw("FIELD(id, " . implode(',', $sort) . ")")
                        ->get();
         }else{
             $tasks = Task::where('project_id', $project_id)->get();
         }
         return response()->json([
            'status' => 'Success',
            'message' => 'task list according Project',
            'tasks' => View::make('include.TaskListInclude', compact('tasks'))->render(),
        ],200);


    }
    public function home()
    {
        $projects = Project::all();
        return view('welcome', compact('projects'));
    }

    public function save_sort(){
        $sort = request()->sort;
        $project_id = request()->project_id;
        $project = Project::find($project_id);
        $project->sort = json_encode($sort);
        $project->save();
        return response()->json([
            'status' => 'Success',
            'message' => 'Sort saved successfully',
        ],200);
    }

}
