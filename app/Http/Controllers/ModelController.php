<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VRModels;
use App\Models\Educations;
use Illuminate\Support\Facades\Storage;
use App\Services\ModelConversionService;

class ModelController extends Controller
{
    protected $modelConversionService;

    public function __construct(ModelConversionService $modelConversionService)
    {
        $this->modelConversionService = $modelConversionService;
    }

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

    // Show form to add new model
    public function showAddModelForm()
    {
        $educations = Educations::all();
        return view('add_model', compact('educations'));
    }

    // Create a new model
    public function store(Request $request)
    {
        $request->validate([
            'modelCreate' => [
                'required',
                'file',
                function ($attribute, $value, $fail) {
                    if (!$value) return;
                    
                    $extension = strtolower($value->getClientOriginalExtension());
                    $modelService = app(ModelConversionService::class);
                    
                    if (!$modelService->isSupported($extension)) {
                        $fail('Filen skal være af typen: ' . implode(', ', $modelService->getSupportedFormats()));
                    }
                }
            ],
            'mtlFile' => [
                'nullable',
                'file',
                function ($attribute, $value, $fail) use ($request) {
                    if (!$value) return;
                    
                    $modelExtension = strtolower($request->file('modelCreate')->getClientOriginalExtension());
                    if ($modelExtension !== 'obj') {
                        $fail('MTL filer kan kun bruges sammen med OBJ filer');
                    }
                    
                    $extension = strtolower($value->getClientOriginalExtension());
                    if ($extension !== 'mtl') {
                        $fail('MTL filen skal være af typen .mtl');
                    }
                }
            ],
            'imageCreate' => 'required|file|mimes:jpeg,png,jpg|max:50000',
            'titleCreate' => 'required|string|max:255',
            'educationCreate' => 'required|array',
            'educationCreate.*' => 'exists:educations,id',
            'descriptionCreate' => 'required|string',
        ]);

        try {
            \Log::debug('Starting model conversion process', [
                'original_file' => $request->file('modelCreate')->getClientOriginalName(),
                'extension' => $request->file('modelCreate')->getClientOriginalExtension(),
                'has_mtl' => $request->hasFile('mtlFile')
            ]);
            
            // Convert the model to GLB format
            $convertedModel = $this->modelConversionService->convertToGlb(
                $request->file('modelCreate'),
                $request->file('mtlFile')
            );
            
            \Log::debug('Model conversion completed', [
                'converted_file' => $convertedModel->getClientOriginalName()
            ]);
            
            // Store the converted model
            $modelPath = $convertedModel->store('models', 'public');
            $imagePath = $request->file('imageCreate')->store('images', 'public');

            // Create the model record
            $model = VRModels::create([
                'title' => $request->input('titleCreate'),
                'description' => $request->input('descriptionCreate'),
                'model_path' => $modelPath,
                'image_path' => $imagePath,
                'user_id' => auth()->id(),
            ]);

            $model->educations()->attach($request->input('educationCreate'));

            return redirect()->route('select.base.object', [
                'modelId' => $model->id,
                'modelPath' => $modelPath
            ])->with('success', '3D Model oprettet succesfuldt');
            
        } catch (\Exception $e) {
            \Log::error('Model creation failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Der opstod en fejl under konvertering: ' . $e->getMessage());
        }
    }

    // Edit model by ID
    public function update(Request $request, $id)
    {
        $request->validate([
            'titleCreate' => 'required|string|max:255',
            'descriptionCreate' => 'required|string',
            'educationCreate' => 'required|array',
            'educationCreate.*' => 'exists:educations,id',
            'modelCreate' => 'nullable|file',
            'imageCreate' => 'nullable|file|mimes:jpeg,png,jpg|max:50000',
        ]);

        try {
            $model = VRModels::findOrFail($id);

            // Update basic information
            $model->title = $request->input('titleCreate');
            $model->description = $request->input('descriptionCreate');

            // Handle new model file upload if provided
            if ($request->hasFile('modelCreate')) {
                \Log::debug('Starting model update conversion process', [
                    'original_file' => $request->file('modelCreate')->getClientOriginalName(),
                    'extension' => $request->file('modelCreate')->getClientOriginalExtension()
                ]);
                
                // Delete the old model file
                Storage::disk('public')->delete($model->model_path);
                
                // Convert the new model to GLB format if needed
                $convertedModel = app(ModelConversionService::class)->convertToGlb(
                    $request->file('modelCreate'),
                    $request->file('mtlFile')
                );
                
                // Store the converted model with original filename
                $originalName = pathinfo($request->file('modelCreate')->getClientOriginalName(), PATHINFO_FILENAME);
                $modelPath = $convertedModel->storeAs('models', $originalName . '.glb', 'public');
                $model->model_path = $modelPath;
            }

            // Handle new image file upload if provided
            if ($request->hasFile('imageCreate')) {
                // Delete the old image
                Storage::disk('public')->delete($model->image_path);
                
                // Store the new image with original filename
                $originalName = pathinfo($request->file('imageCreate')->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $request->file('imageCreate')->getClientOriginalExtension();
                $imagePath = $request->file('imageCreate')->storeAs('images', $originalName . '.' . $extension, 'public');
                $model->image_path = $imagePath;
            }

            $model->save();

            // Update education relationships
            $model->educations()->sync($request->input('educationCreate'));

            // Only redirect to base object selection if the model file was changed
            if ($request->hasFile('modelCreate')) {
                return redirect()->route('select.base.object', [
                    'modelId' => $model->id,
                    'modelPath' => $model->model_path
                ])->with('success', '3D Model opdateret succesfuldt');
            }

            // Otherwise redirect back to home
            return redirect()->route('home')->with('success', '3D Model opdateret succesfuldt');
            
        } catch (\Exception $e) {
            \Log::error('Model update failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Der opstod en fejl under opdatering: ' . $e->getMessage());
        }
    }

    // Delete model by ID
    public function deleteModelByID($id)
    {
        $model = VRModels::find($id);

        if (!$model) {
            return redirect('/')->with('error', 'Model ikke fundet');
        }

        try {
            // Slet tilknyttede filer
            Storage::disk('public')->delete([$model->model_path, $model->image_path]);

            // Frakobl relaterede uddannelser
            $model->educations()->detach();

            $model->delete();

            return redirect('/')->with('success', 'Model slettet succesfuldt');
        } catch (\Exception $e) {
            \Log::error('Model deletion failed: ' . $e->getMessage());
            return redirect('/')->with('error', 'Der opstod en fejl: ' . $e->getMessage());
        }
    }

    // Show select base object page
    public function showSelectBaseObject($modelId)
    {
        $model = VRModels::findOrFail($modelId);

        return view('select_base_object', [
            'modelId' => $modelId,
            'modelPath' => $model->model_path
        ]);
    }

    // Complete model upload
    public function completeModelUpload(Request $request, $modelId)
    {
        try {
            // Ensure JSON is always returned
            if (!$request->expectsJson()) {
                return response()->json(['success' => false, 'error' => 'Invalid request type'], 400);
            }

            $model = VRModels::findOrFail($modelId);
            $baseObject = $request->input('baseObject');

            $cleanBaseObject = $baseObject ? str_replace('Action', '', $baseObject) : null;

            $model->update([
                'base_object' => $cleanBaseObject ? trim($cleanBaseObject) : null
            ]);

            return response()->json(['success' => true, 'redirect' => route('home')]);

        } catch (\Exception $e) {
            \Log::error('Failed to update base object', [
                'error' => $e->getMessage(),
                'modelId' => $modelId,
                'baseObject' => $baseObject ?? null
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Der opstod en fejl ved opdatering af base objekt'
            ], 500);
        }
    }
}
