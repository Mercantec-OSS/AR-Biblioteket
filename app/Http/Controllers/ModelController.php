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
        // Validate file types
        $request->validate([
            'modelCreate' => [
                'required',
                'file',
                'mimes:glb',
                'max:50000'
            ],
            'imageCreate' => [
                'required',
                'file',
                'mimes:jpeg,png,jpg',
                'max:50000'
            ],
            'titleCreate' => 'required|string|max:255',
            'educationCreate' => 'required|string',
            'descriptionCreate' => 'required|string'
        ], [
            'modelCreate.mimes' => 'Modelfilen skal være i GLB-format',
            'modelCreate.max' => 'Modelfilen må ikke være større end 50MB',
            'imageCreate.mimes' => 'Billedfilen skal være i JPEG, PNG eller JPG format',
            'imageCreate.max' => 'Billedfilen må ikke være større end 50MB',
        ]);

        try {
            // Store files in public storage
            $modelPath = $request->file('modelCreate')->store('models', 'public');
            $imagePath = $request->file('imageCreate')->store('images', 'public');

            // Create and save the model with correct timezone
            $model = new VRModels();
            $model->title = $request->input('titleCreate');
            $model->education = $request->input('educationCreate');
            $model->description = $request->input('descriptionCreate');
            $model->model_path = $modelPath;
            $model->image_path = $imagePath;
            $model->created_at = now(); // Dette vil nu bruge den korrekte tidszone
            $model->user_id = 1; // Temporarily hardcoded - should come from authenticated user

            if ($model->save()) {
                return redirect('/')->with('success', 'Model tilføjet succesfuldt');
            }

            return back()->with('error', 'Failed to add model');
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Model creation failed: ' . $e->getMessage());
            return back()->with('error', 'Der opstod en fejl: ' . $e->getMessage());
        }
    }

    // Edit model by ID
    public function update(Request $request, $id)
    {
        $model = VRModels::find($id);

        if ($model) {
            // Validate the request
            $validationRules = [
                'titleCreate' => 'required|string|max:255',
                'educationCreate' => 'required|string',
                'descriptionCreate' => 'required|string'
            ];

            // Add file validation rules only if files are being uploaded
            if ($request->hasFile('modelCreate')) {
                $validationRules['modelCreate'] = 'file|mimes:glb|max:50000';
            }
            if ($request->hasFile('imageCreate')) {
                $validationRules['imageCreate'] = 'file|mimes:jpeg,png,jpg|max:50000';
            }

            $request->validate($validationRules);

            try {
                $model->title = $request->input('titleCreate');
                $model->education = $request->input('educationCreate');
                $model->description = $request->input('descriptionCreate');

                // Handle file updates
                if ($request->hasFile('modelCreate')) {
                    Storage::disk('public')->delete($model->model_path);
                    $model->model_path = $request->file('modelCreate')->store('models', 'public');
                }
                if ($request->hasFile('imageCreate')) {
                    Storage::disk('public')->delete($model->image_path);
                    $model->image_path = $request->file('imageCreate')->store('images', 'public');
                }

                $model->save();
                return redirect('/')->with('success', 'Model opdateret succesfuldt');
            } catch (\Exception $e) {
                \Log::error('Model update failed: ' . $e->getMessage());
                return back()->with('error', 'Der opstod en fejl: ' . $e->getMessage());
            }
        }

        return back()->with('error', 'Model ikke fundet');
    }

    // Delete model by ID
    public function deleteModelByID($id)
    {
        $model = VRModels::find($id);
        if ($model) {
            // Delete associated files
            Storage::disk('public')->delete($model->model_path);
            Storage::disk('public')->delete($model->image_path);
            
            $model->delete();
            return redirect('/')->with('success', 'Model slettet succesfuldt');
        }
        return redirect('/')->with('error', 'Model ikke fundet');
    }
}
