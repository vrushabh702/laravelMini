<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Use App\Http\Controller\TaskController;

Route::get('tasks',[TaskController::class,'index']);
Route::get('taskEdit/{id}/',[TaskController::class, 'edit']);
Route::post('taskStore/',[TaskController::class, 'store']);
Route::get('taskDelete/{id}/',[TaskController::class, 'destroy']);

