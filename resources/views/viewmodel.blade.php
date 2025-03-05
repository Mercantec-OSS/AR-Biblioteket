@extends('layouts.main')

@section('title', $model->title)

@section('head')
    <link rel="stylesheet" href="{{ asset('css/modelviewer.css') }}">
@endsection

@section('content')
<div class="model-viewer-container">
    <div class="page-header">
        <h1>{{ $model->title }}</h1>
        @if($model->educations->isNotEmpty())
            <div class="education-tags">
                @foreach($model->educations as $education)
                    <span class="education-tag">{{ $education->title }}</span>
                    @if(!$loop->last)
                        <span class="education-separator">•</span>
                    @endif
                @endforeach
            </div>
        @endif
        <p class="description">{{ $model->description ?? 'No description available' }}</p>
    </div>

    <div class="model-content">
        <div class="controls">
            <select id="animation-select" class="animation-dropdown">
                <option value="">No Animation</option>
            </select>
        </div>

        <model-viewer
            id="model-viewer"
            src="{{ secure_asset('storage/' . $model->model_path) }}"
            alt="{{ $model->title }}"
            ar
            ar-modes="webxr scene-viewer quick-look"
            camera-controls
            shadow-intensity="1"
            auto-rotate
            camera-orbit="45deg 55deg 2.5m"
            animation-name=""
        >
            <button slot="ar-button" class="ar-button">
                View in AR
            </button>
        </model-viewer>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const modelViewer = document.querySelector('#model-viewer');
    const animationSelect = document.querySelector('#animation-select');

    modelViewer.addEventListener('load', () => {
        const animationNames = modelViewer.availableAnimations || [];
        
        while (animationSelect.options.length > 1) {
            animationSelect.remove(1);
        }

        animationNames.forEach((animationName) => {
            const option = document.createElement('option');
            option.value = animationName;
            option.text = animationName
                .replace(/_/g, ' ')
                .replace(/([A-Z])/g, ' $1')
                .trim();
            animationSelect.appendChild(option);
        });
    });

    animationSelect.addEventListener('change', (event) => {
        modelViewer.animationName = event.target.value;
        if (event.target.value) {
            modelViewer.play();
        } else {
            modelViewer.stop();
        }
    });
});
</script>
@endsection