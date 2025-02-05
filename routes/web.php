<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ModelController;
use App\Http\Controllers\JWTAuthController;
use App\Http\Middleware\JwtMiddleware;

// Home route
Route::get('/', function () {
    $models = \App\Models\VRModels::all();
    return view('home', ['models' => $models]);
})->name('home');

// Add model page
Route::get('/add_model', function () {
    return view('add_model');
})->middleware('jwt.auth')->name('add_model');

// Edit model page
Route::get('/edit_model', function () {
    return view('edit_model');
})->middleware('jwt.auth')->name('edit_model');

// Edit model with ID
Route::get('/edit_model/{id}', function ($id) {
    $model = \App\Models\VRModels::find($id);
    return view('edit_model', ['model' => $model]);
})->middleware('jwt.auth')->name('edit_model_with_id');

// User-related routes
Route::view('createUser', 'createUser')->name('createUser');
Route::view('login', 'login')->name('login');
Route::post('createUser', [UserController::class, 'createUser'])->name('createUser.post');

// Move the API routes outside of the /api prefix for auth
Route::post('/login', [JWTAuthController::class, 'login'])->name('login');
Route::post('/register', [JWTAuthController::class, 'register'])->name('register');

// Keep the protected API routes in the api group
Route::group(['prefix' => 'api', 'middleware' => ['jwt.auth']], function () {
    Route::post('logout', [JWTAuthController::class, 'logout'])->name('api.logout');
    Route::get('user', [JWTAuthController::class, 'getUser'])->name('api.user');
});

// Protected web routes (for example, only authenticated users can access)
Route::middleware(['jwt.auth'])->group(function () {
    Route::get('/protected', function () {
        return response()->json(['message' => 'You have accessed a protected route']);
    })->name('protected');
});

// Other model-related routes (no authentication required for these, since they may be public)
Route::post('/add_model', [ModelController::class, 'store'])->name('add_model.store');
Route::put('/edit_model/{id}', [ModelController::class, 'update'])->name('edit_model.update');
Route::get('/model/{id}', [ModelController::class, 'getModelByID'])->name('model.show');
Route::get('/models', [ModelController::class, 'getAllModels'])->name('models.index');
Route::delete('/model/{id}', [ModelController::class, 'deleteModelByID'])->name('model.delete');

Route::middleware(['jwt.auth'])->get('/getUser', [JWTAuthController::class, 'getUser']);
