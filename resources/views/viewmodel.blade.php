@extends('layouts.main')

@section('title', $model->title)

@section('content')
<div class="container">
    <div class="model-container">
        <h2 class="model-title">{{ $model->title }}</h2>

        <!-- 3D Model Viewer -->
        <model-viewer 
            poster="{{ asset('storage/images/' . $model->image_path) }}"
            src="{{ asset('storage/models/' . $model->model_path)}}"
            alt="{{ $model->title }}"
            autoplay 
            ar
            ar-modes="webxr scene-viewer"
            camera-controls
            touch-action="pan-y"
            scale="0.2 0.2 0.2"
            shadow-intensity="1">
        </model-viewer>

        <div class="model-description">
            <p><strong>Education:</strong> {{ $model->education }}</p>
            <p><strong>Description:</strong> {{ $model->description }}</p>
            <p><strong>Uploaded:</strong> {{ \Carbon\Carbon::parse($model->created_at)->format('d/m/Y') }}</p>
            <p><strong>Last Edited:</strong> {{ \Carbon\Carbon::parse($model->updated_at)->format('d/m/Y') }}</p>
        </div>
    </div>
</div>
@endsection
