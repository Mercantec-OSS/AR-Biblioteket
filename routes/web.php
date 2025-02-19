<?php

use Illuminate\Support\Facades\Route;
use App\Models\VRModels;
use App\Models\Educations;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ModelController;
use App\Http\Controllers\JWTAuthController;
use App\Http\Middleware\JwtMiddleware;
use Illuminate\Http\Request;


// Home route
Route::get('/', function (JWTAuthController $authController) {
    $models = VRModels::with('educations')->get();
    $educations = Educations::orderBy('title')->get();
    $isAuthenticated = $authController->isAuthenticated(request());
    return view('home', [
        'models' => $models,
        'educations' => $educations,
        'isAuthenticated' => $isAuthenticated
    ]);
})->name('home');

Route::get('/add_model', function (JWTAuthController $authController) {
    // Check if the user is authenticated
    $isAuthenticated = $authController->isAuthenticated(request());
    
    if (!$isAuthenticated) {
        return redirect('/login')->withErrors(['message' => 'Unauthorized access. Please log in.']);
    }

    // Call the controller method to show the form
    return app(ModelController::class)->showAddModelForm(); // This calls the method from the controller
})->name('add_model');

// Add model POST route
Route::post('/add_model', function(Request $request, JWTAuthController $authController, ModelController $modelController) {
    $isAuthenticated = $authController->isAuthenticated($request);
    if (!$isAuthenticated) {
        return redirect('/login')->withErrors(['message' => 'Unauthorized access. Please log in.']);
    }
    return $modelController->store($request);
})->name('add_model.store');

// Edit model page
Route::get('/edit_model', function (JWTAuthController $authController) {
    $isAuthenticated = $authController->isAuthenticated(request());
    if (!$isAuthenticated) {
        return redirect('/login')->withErrors(['message' => 'Unauthorized access. Please log in.']);
    }
    return view('edit_model', ['isAuthenticated' => $isAuthenticated]);
})->name('edit_model');

// Edit model with ID
Route::get('/edit_model/{id}', function ($id, JWTAuthController $authController) {
    $isAuthenticated = $authController->isAuthenticated(request());
    if (!$isAuthenticated) {
        return redirect('/login')->withErrors(['message' => 'Unauthorized access. Please log in.']);
    }
    $model = VRModels::with('educations')->find($id);
    $educations = Educations::orderBy('title')->get();
    return view('edit_model', [
        'model' => $model,
        'educations' => $educations,
        'isAuthenticated' => $isAuthenticated
    ]);
})->name('edit_model_with_id');

// Edit model PUT route
Route::put('/edit_model/{id}', function(Request $request, $id, JWTAuthController $authController, ModelController $modelController) {
    $isAuthenticated = $authController->isAuthenticated($request);
    if (!$isAuthenticated) {
        return redirect('/login')->withErrors(['message' => 'Unauthorized access. Please log in.']);
    }
    return $modelController->update($request, $id);
})->name('edit_model.update');

// View single model route (public)
Route::get('/model/{id}', [ModelController::class, 'getModelByID'])->name('model.view');

// Delete model route
Route::delete('/model/{id}', function(Request $request, $id, JWTAuthController $authController, ModelController $modelController) {
    $isAuthenticated = $authController->isAuthenticated($request);
    if (!$isAuthenticated) {
        return redirect('/login')->withErrors(['message' => 'Unauthorized access. Please log in.']);
    }
    return $modelController->deleteModelByID($id);
})->name('model.delete');

// Keep the protected API routes in the api group
Route::group(['prefix' => 'api', 'middleware' => ['jwt.auth']], function () {
    Route::post('logout', [JWTAuthController::class, 'logout'])->name('api.logout');
    Route::get('user', [JWTAuthController::class, 'getUser'])->name('api.user');
});

// User-related routes
Route::view('createUser', 'createUser', ['isAuthenticated' => false])->name('createUser');
Route::view('login', 'login', ['isAuthenticated' => false])->name('login');
Route::post('createUser', [UserController::class, 'createUser'])->name('createUser.post');
Route::post('/login', [JWTAuthController::class, 'login'])->name('login');
Route::post('/register', [JWTAuthController::class, 'register'])->name('register');

// Logout route
Route::post('/logout', [JWTAuthController::class, 'logout'])->name('logout');


// Model-related routes
Route::get('/models', [ModelController::class, 'getAllModels']);
Route::delete('/model/{id}', [ModelController::class, 'deleteModelByID']);
Route::get('/select-base-object/{modelId}', [ModelController::class, 'showSelectBaseObject'])
    ->name('select.base.object');
Route::post('/complete-model/{modelId}', [ModelController::class, 'completeModelUpload'])
    ->name('complete.model.upload');

// API routes
Route::get('/api/models', [VRModelsController::class, 'index']);

