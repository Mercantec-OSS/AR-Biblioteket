@extends('layouts.main')

@section('title', $model->title)

@section('content')
<div class="model-viewer-container">
    <div class="page-header">
        <h1>{{ $model->title }}</h1>
        <p class="education-tag">{{ $model->education }}</p>
        <p class="description">{{ $model->description }}</p>
    </div>

    <div class="model-content">
        <div class="controls">
            <select id="animation-select" class="animation-dropdown">
                <option value="">No Animation</option>
            </select>
        </div>

        <model-viewer
            id="model-viewer"
            src="{{ asset('storage/' . $model->model_path) }}"
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
    const modelViewer = document.querySelector('#model-viewer');
    const animationSelect = document.querySelector('#animation-select');

    // When the model is loaded, populate the animation dropdown
    modelViewer.addEventListener('load', () => {
        const animationNames = modelViewer.availableAnimations;
        
        // Clear existing options (except "No Animation")
        while (animationSelect.options.length > 1) {
            animationSelect.remove(1);
        }

        // Add all available animations to the dropdown
        animationNames.forEach((animationName) => {
            const option = document.createElement('option');
            option.value = animationName;
            option.text = animationName.replace(/_/g, ' ').replace(/([A-Z])/g, ' $1').trim();
            animationSelect.appendChild(option);
        });
    });

    // When an animation is selected, play it
    animationSelect.addEventListener('change', (event) => {
        modelViewer.animationName = event.target.value;
        if (event.target.value) {
            modelViewer.play();
        }
    });
</script>
@endsection
