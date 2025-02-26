<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VRModels;
use App\Models\Educations;
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
        // Validate the request
        $request->validate([
            'modelCreate' => 'required|file|mimes:glb|max:50000',
            'imageCreate' => 'required|file|mimes:jpeg,png,jpg|max:50000',
            'titleCreate' => 'required|string|max:255',
            'educationCreate' => 'required|array', // Allow multiple education IDs
            'educationCreate.*' => 'exists:educations,id', // Ensure valid education IDs
            'descriptionCreate' => 'required|string'
        ]);

        try {
            // File upload handling
            if ($request->hasFile('modelCreate') && $request->file('modelCreate')->isValid()) {
                $modelPath = $request->file('modelCreate')->store('models', 'public');
            } else {
                throw new \Exception('3D model file not uploaded or invalid');
            }

            if ($request->hasFile('imageCreate') && $request->file('imageCreate')->isValid()) {
                $imagePath = $request->file('imageCreate')->store('images', 'public');
            } else {
                throw new \Exception('Image file not uploaded or invalid');
            }

            // Get authenticated user's ID
            $user = auth()->user();
            if (!$user) {
                throw new \Exception('User not authenticated');
            }

            // Create new model
            $model = new VRModels();
            $model->title = $request->input('titleCreate');
            $model->description = $request->input('descriptionCreate');
            $model->model_path = $modelPath;
            $model->image_path = $imagePath;
            $model->user_id = $user->id; // Use authenticated user's ID

            // Save the model and handle success
            if ($model->save()) {
                // Attach education using the pivot table
                $model->educations()->attach($request->input('educationCreate')); // Handle multiple educations
                return redirect()->route('select.base.object', [
                    'modelId' => $model->id,
                    'modelPath' => $modelPath
                ]);
            } else {
                throw new \Exception('Failed to save model');
            }
            
        } catch (\Exception $e) {
            \Log::error('Model creation failed: ' . $e->getMessage());
            return back()->with('error', 'Der opstod en fejl: ' . $e->getMessage());
        }
    }

    public function showAddModelForm()
    {
        $educations = Educations::all(); // Fetch all educations from the database
        return view('add_model', compact('educations')); // Pass the data to the view
    }

    // Edit model by ID
    public function update(Request $request, $id)
    {
        $model = VRModels::find($id);

        if ($model) {
            // Validate the request
            $validationRules = [
                'titleCreate' => 'required|string|max:255',
                'educationCreate' => 'required|array',
                'educationCreate.*' => 'exists:educations,id',
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
                $model->description = $request->input('descriptionCreate');

                // Handle file uploads if present
                if ($request->hasFile('modelCreate')) {
                    Storage::disk('public')->delete($model->model_path);
                    $model->model_path = $request->file('modelCreate')->store('models', 'public');
                    $modelUpdated = true;
                }

                if ($request->hasFile('imageCreate')) {
                    Storage::disk('public')->delete($model->image_path);
                    $model->image_path = $request->file('imageCreate')->store('images', 'public');
                }

                $model->save();

                // Sync educations
                $model->educations()->sync($request->input('educationCreate'));

                // Redirect based on whether model was updated
                if (isset($modelUpdated) && $modelUpdated) {
                    return redirect()->route('select.base.object', [
                        'modelId' => $model->id,
                        'modelPath' => $model->model_path
                    ]);
                }

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
