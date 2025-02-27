<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VRModels;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Http\Response;

class VRModelsController extends Controller
{
    public function index(): JsonResponse
    {
        $models = VRModels::all();
        
        return response()->json([
            'status' => 'success',
            'data' => $models,
        ]);
    }

   public function downloadModel($id): BinaryFileResponse
{
    $model = VRModels::find($id);

    if (!$model) {
        abort(404, 'Model not found in database');
    }

    $path = storage_path('app/public/' . $model->model_path);

    if (!file_exists($path)) {
        abort(404, 'File not found');
    }

    return response()->download($path, basename($model->model_path));
}
}
