<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\VRModels;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Educations;
use Illuminate\Support\Facades\Storage;
use App\Services\ModelConversionService;


class PdfController extends Controller
{
    public function generateModelPoster(VRModels $model, Educations $educations)
    {
        $modelUrl = "https://arbibliotek.socdata.dk/model/{$model->id}";
        $modelName = $model->title;
        // Get the first education associated with this model through the pivot table
        $education = $model->educations()->first();
        $educationTitle = $education ? $education->title : 'Uddannelse ikke fundet.';
        $educationColor = $education ? $education->color : '#000000';

        $data = [
            'modelName' => $modelName,
            'model' => $model,
            'education' => $educationTitle,
            'educationColor' => $educationColor,
            'modelImage' => storage_path('app/public/' . $model->image_path),
            'logo' => public_path('/images/automationTeknologiLogo.png'),
            'QrCodeText' => 'Scan og se modellen i AR',
            'QrCode' => base64_encode(QrCode::format('svg')->size(200)->errorCorrection('H')->generate($modelUrl)),
            'path' => $modelUrl,
        ];

        $modelName = $data['modelName'];

        $pdf = Pdf::loadView('model-poster', $data);
        return $pdf->stream($model->name . '-poster.pdf');
    }
}
