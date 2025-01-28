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
        if ($model) {
            return 'Model found: ' . $model->title;
        } else {
            return 'Model not found';
        }
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
            'title' => 'required|string',
            'education' => 'required|string',
            'description' => 'required|string',
            'model' => 'required|file|mimes:glb', 
            'image' => 'required|file|mimes:jpeg,png,jpg'
        ]);

        // Handle file uploads
        $modelPath = $request->file('modelCreate')->store('models');
        $imagePath = $request->file('imageCreate')->store('images');

        $model = new VRModels();
        $model->title = $request->input('titleCreate');
        $model->education = $request->input('educationCreate');
        $model->description = $request->input('descriptionCreate');
        $model->model_path = $modelPath;
        $model->image_path = $imagePath;

        if ($model->save()) {
            return redirect('/')->with('message', 'Model added successfully');
        } else {
            return 'Model could not be created';
        }
    }

    // Edit model by ID
    public function update(Request $request, $id)
    {
        $model = VRModels::find($id);
        if ($model) {
            $model->title = $request->input('title', $model->title);
            $model->education = $request->input('education', $model->education);
            $model->description = $request->input('description', $model->description);

            // Handle file updates if new files are uploaded
            if ($request->hasFile('model')) {
                $model->model_path = $request->file('model')->store('models');
            }
            if ($request->hasFile('image')) {
                $model->image_path = $request->file('image')->store('images');
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
