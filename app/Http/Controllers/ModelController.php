<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VRModels;
use Illuminate\Support\Facades\Storage;

class ModelController extends Controller
{
    // Get model by ID
    public function getModelByID($id)
    {
        $model = VRModels::find($id);
        return $model ? 'Model found: ' . $model->title : 'Model not found';
    }

    // Get all models
    public function getAllModels()
    {
        $models = VRModels::all();
        return 'Models: ' . $models->pluck('title')->implode(', ');
    }

    // Create a new model
    public function store(Request $request)
    {
        $request->validate([
            'titleCreate' => 'required|string',
            'educationCreate' => 'required|string',
            'descriptionCreate' => 'required|string',
            'modelCreate' => 'required|file',  
            'imageCreate' => 'required|file|mimes:jpeg,png,jpg'
        ]);

        try {
            // Store files in public storage
            $modelPath = $request->file('modelCreate')->store('models', 'public');
            $imagePath = $request->file('imageCreate')->store('images', 'public');

            // Create and save the model
            $model = new VRModels();
            $model->title = $request->input('titleCreate');
            $model->education = $request->input('educationCreate');
            $model->description = $request->input('descriptionCreate');
            $model->model_path = $modelPath;
            $model->image_path = $imagePath;
            $model->user_id = 1; // Temporarily hardcoded - should come from authenticated user

            if ($model->save()) {
                return redirect('/')->with('success', 'Model added successfully');
            }

            return back()->with('error', 'Failed to add model');
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Model creation failed: ' . $e->getMessage());
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // Edit model by ID
    public function update(Request $request, $id)
    {
        $model = VRModels::find($id);

        if ($model) {
            $model->title = $request->input('titleCreate', $model->title);
            $model->education = $request->input('educationCreate', $model->education);
            $model->description = $request->input('descriptionCreate', $model->description);

            // Handle file updates
            if ($request->hasFile('modelCreate')) {
                Storage::delete($model->model_path); // Delete old file
                $model->model_path = $request->file('modelCreate')->store('models');
            }
            if ($request->hasFile('imageCreate')) {
                Storage::delete($model->image_path); // Delete old file
                $model->image_path = $request->file('imageCreate')->store('images');
            }

            $model->save();
            return 'Model updated successfully';
        } else {
            return 'Model not found';
        }
    }

    // Delete model by ID
    public function deleteModelByID($id)
    {
        $model = VRModels::find($id);
        if ($model) {
            // Delete associated files
            Storage::delete($model->model_path);
            Storage::delete($model->image_path);

            $model->delete();
            return 'Model deleted successfully';
        } else {
            return 'Model not found';
        }
    }
}
