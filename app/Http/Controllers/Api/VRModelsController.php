<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VRModels;
use Illuminate\Http\JsonResponse;

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
}
