@extends('layouts.main')

@section('title', $model->title)

@section('content')
<div class="container">
    <div class="model-container">
        <h2 class="model-title">{{ $model->title }}</h2>
        
        <!-- Model Image -->
        <img src="{{ asset('storage/images/' . $model->image_path) }}" 
             alt="{{ $model->title }}" 
             class="model-image">
        
        <!-- Model Viewer -->
        <model-viewer 
            src="{{ asset('storage/models/' . $model->model_path) }}"
            alt="{{ $model->title }}"
            ar
            ar-modes="webxr scene-viewer quick-look"
            camera-controls
            auto-rotate
            shadow-intensity="1">
        </model-viewer>

        <div class="model-description">
            <p><strong>Education:</strong> {{ $model->education }}</p>
            <p><strong>Description:</strong> {{ $model->description }}</p>
            <p><strong>Uploaded:</strong> {{ \Carbon\Carbon::parse($model->uploaded_at)->format('d/m/Y') }}</p>
            <p><strong>Last Edited:</strong> {{ \Carbon\Carbon::parse($model->updated_at)->format('d/m/Y') }}</p>
        </div>
    </div>
</div>
@endsection
