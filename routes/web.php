<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ModelController;

Route::get('/', function () {
    return view('home');
});

Route::get('/add_model', function () {
    return view('add_model');
});

Route::get('/edit_model', function () {
    return view('edit_model');
});

Route::view('createUser','createUser');
Route::view('login','login');

Route::post('createUser', [UserController::class, 'createUser']);
Route::post('login', [UserController::class, 'loginUser']);

// User Routes
Route::get('/user/{id}', [UserController::class, 'getUserByID']);
Route::get('/users', [UserController::class, 'getAllUsers']);
Route::put('/user/{id}', [UserController::class, 'editUserByID']);
Route::delete('/user/{id}', [UserController::class, 'deleteUserByID']);

// Model Routes
Route::get('/model/{id}', [ModelController::class, 'getModelByID']);
Route::get('/models', [ModelController::class, 'getAllModels']);
Route::post('/add_model', [ModelController::class, 'store']);
Route::post('/edit_model/{id}', [ModelController::class, 'update']);
Route::delete('/model/{id}', [ModelController::class, 'deleteModelByID']);