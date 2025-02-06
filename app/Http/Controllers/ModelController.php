<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VRModels;
use App\Models\Education; // Import the Education model
use Illuminate\Support\Facades\Storage;

class ModelController extends Controller
{
    // Get model by ID  
    public function getModelByID($id, JWTAuthController $authController)
    {
        $model = VRModels::findOrFail($id);
        $isAuthenticated = $authController->isAuthenticated(request());
        return view('viewmodel', [
            'model' => $model,
            'isAuthenticated' => $isAuthenticated
        ]);
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
        // Validate file types and other inputs
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
            'educationCreate' => 'required|array', // Array of education IDs
            'educationCreate.*' => 'exists:educations,id', // Validate each education ID
            'descriptionCreate' => 'required|string'
        ]);

        try {
            // Store files in public storage
            $modelPath = $request->file('modelCreate')->store('models', 'public');
            $imagePath = $request->file('imageCreate')->store('images', 'public');

            // Create and save the model
            $model = new VRModels();
            $model->title = $request->input('titleCreate');
            $model->education = $request->input('educationCreate'); // This field will be handled via pivot table now
            $model->description = $request->input('descriptionCreate');
            $model->model_path = $modelPath;
            $model->image_path = $imagePath;
            $model->user_id = auth()->id(); // Get the authenticated user's ID
            $model->created_at = now();


            if ($model->save()) {
                // Attach the selected educations to the model
                $model->educations()->attach($request->input('educationCreate'));

                // Redirect to select base object page with model ID and path
                return redirect()->route('select.base.object', [
                    'modelId' => $model->id,
                    'modelPath' => $modelPath
                ]);
            }
        } catch (\Exception $e) {
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
                'educationCreate' => 'required|array', // Array of education IDs
                'educationCreate.*' => 'exists:educations,id', // Validate each education ID
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
                $model->education = $request->input('educationCreate'); // Handle education changes via pivot
                $model->description = $request->input('descriptionCreate');

                // Check if a new 3D model was uploaded
                $modelUpdated = false;
                if ($request->hasFile('modelCreate')) {
                    Storage::disk('public')->delete($model->model_path);
                    $model->model_path = $request->file('modelCreate')->store('models', 'public');
                    $modelUpdated = true;
                }

                // Handle image update
                if ($request->hasFile('imageCreate')) {
                    Storage::disk('public')->delete($model->image_path);
                    $model->image_path = $request->file('imageCreate')->store('images', 'public');
                }

                $model->save();

                // Sync educations to the VR model (attach if new, remove old if any)
                if ($request->has('educationCreate')) {
                    $model->educations()->sync($request->input('educationCreate')); // Sync educations
                }

                // If 3D model was updated, redirect to select base object page
                if ($modelUpdated) {
                    return redirect()->route('select.base.object', [
                        'modelId' => $model->id,
                        'modelPath' => $model->model_path
                    ]);
                }

                // Otherwise, redirect to home page
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

    public function showSelectBaseObject($modelId)
    {
        $model = VRModels::findOrFail($modelId);
        return view('select_base_object', [
            'modelId' => $modelId,
            'modelPath' => $model->model_path
        ]);
    }

    public function completeModelUpload(Request $request, $modelId)
    {
        $model = VRModels::findOrFail($modelId);
        
        // Get the selected base object and remove "Action" from it
        $baseObject = $request->input('baseObject');
        $cleanBaseObject = str_replace('Action', '', $baseObject);
        
        // Save the cleaned base object name
        $model->base_object = trim($cleanBaseObject);
        $model->save();
        
        return redirect('/')->with('success', 'Model tilføjet succesfuldt');
    }
}
