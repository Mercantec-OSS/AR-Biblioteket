@extends('layouts.main')

@section('title', 'Vælg Base Objekt')

@section('content')
<div class="page-container">
    <div class="form-container">
        <h1 class="page-title">Vælg Base Objekt</h1>
        
        @if ($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="model-content">
            <div class="controls">
                <select id="baseObject" name="baseObject" class="animation-dropdown">
                    <option value="">Ingen base objekt</option>
                </select>
            </div>

            <model-viewer
                id="model-viewer"
                src="{{ secure_asset('storage/' . $modelPath) }}"  
                camera-controls
                shadow-intensity="1"
                auto-rotate
                camera-orbit="45deg 55deg 2.5m"
            ></model-viewer>
        </div>

        <form method="POST" action="{{ route('complete.model.upload', ['modelId' => $modelId]) }}">
 
            @csrf
            <input type="hidden" id="selectedBaseObject" name="baseObject" value="">
            <div class="form-actions">
                <button type="submit">Færdiggør Model Upload</button>
            </div>
        </form>
    </div>
</div>

<script>
    const modelViewer = document.querySelector('#model-viewer');
    const baseObjectSelect = document.querySelector('#baseObject');
    const selectedBaseObjectInput = document.querySelector('#selectedBaseObject');

    // When the model is loaded, populate the animation dropdown
    modelViewer.addEventListener('load', () => {
        const animationNames = modelViewer.availableAnimations;
        
        // Clear existing options (except the default)
        while (baseObjectSelect.options.length > 1) {
            baseObjectSelect.remove(1);
        }

        // Add animations as options, removing "Action" text
        animationNames.forEach((name) => {
            const cleanName = name.replace('Action', '').trim();
            const option = document.createElement('option');
            option.value = name;
            option.text = cleanName;
            baseObjectSelect.appendChild(option);
        });
    });

    // When a base object is selected, update the hidden input
    baseObjectSelect.addEventListener('change', (event) => {
        selectedBaseObjectInput.value = event.target.value;
        
        // Play the selected animation to visualize the part
        modelViewer.animationName = event.target.value;
        if (event.target.value) {
            modelViewer.play();
        }
    });
</script>
@endsection
