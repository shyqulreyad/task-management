<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/',[TaskController::class, 'home'])->name('home');
Route::post('project/tasks',[TaskController::class, 'task_list'])->name('project.task.list');
Route::post('task/sort',[TaskController::class, 'save_sort'])->name('task.sort');
Route::post('task/delete',[TaskController::class, 'delete_task'])->name('task.delete');
Route::post('task/create',[TaskController::class, 'create_task'])->name('task.create');
Route::post('task/update',[TaskController::class, 'update_task'])->name('task.update');
Route::post('project/create',[TaskController::class, 'create_project'])->name('project.create');

