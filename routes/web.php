<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ModelController;

// Home route
Route::get('/', function () {
    $models = \App\Models\VRModels::all();
    return view('home', ['models' => $models]);
});

// Add model page
Route::get('/add_model', function () {
    return view('add_model');
});

// Edit model page
Route::get('/edit_model', function () {
    return view('edit_model');
});

// Edit model page
Route::get('/edit_model/{id}', function ($id) {
    $model = \App\Models\VRModels::find($id);
    return view('edit_model', ['model' => $model]);
});

// User-related routes
Route::view('createUser', 'createUser');
Route::view('login', 'login');
Route::post('createUser', [UserController::class, 'createUser']);
//Route::post('login', [UserController::class, 'loginUser']);
Route::get('/user/{id}', [UserController::class, 'getUserByID']);
Route::get('/users', [UserController::class, 'getAllUsers']);
Route::put('/user/{id}', [UserController::class, 'editUserByID']);
Route::delete('/user/{id}', [UserController::class, 'deleteUserByID']);

// Model-related routes
Route::post('/add_model', [ModelController::class, 'store']);
Route::put('/edit_model/{id}', [ModelController::class, 'update']);
Route::get('/model/{id}', [ModelController::class, 'getModelByID']);
Route::get('/models', [ModelController::class, 'getAllModels']);
Route::delete('/model/{id}', [ModelController::class, 'deleteModelByID']);




