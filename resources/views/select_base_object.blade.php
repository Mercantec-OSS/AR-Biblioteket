@extends('layouts.main')

@section('title', 'Vælg Base Objekt')

@section('content')
<div class="page-container">
    <div class="form-container">
        <h1 class="page-title">Vælg Base Objekt</h1>
        
        <!-- Viser fejlmeddelelser, hvis der er nogen -->
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
                <!-- Dropdown til valg af base objekt -->
                <select id="baseObject" name="baseObject" required class="animation-dropdown">
                    <option value="">Vælg en del</option>
                </select>
            </div>

            <!-- Model-viewer til at vise 3D-modellen -->
            <model-viewer
                id="model-viewer"
                src="{{ secure_asset('storage/' . $modelPath) }}"
                camera-controls
                shadow-intensity="1"
                auto-rotate
                camera-orbit="45deg 55deg 2.5m"
            ></model-viewer>
        </div>

        <!-- Formular til at færdiggøre upload -->
        <form id="completeModelForm" method="POST" action="{{ route('complete.model.upload', ['modelId' => $modelId]) }}">
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
    const form = document.querySelector('#completeModelForm');

    // Når modellen er indlæst, udfyld dropdown med animationer
    modelViewer.addEventListener('load', () => {
        const animationNames = modelViewer.availableAnimations;
        
        // Ryd eksisterende muligheder (undtagen standard)
        while (baseObjectSelect.options.length > 1) {
            baseObjectSelect.remove(1);
        }

        // Tilføj animationer som valgmuligheder og fjern "Action" fra navnet
        animationNames.forEach((name) => {
            const cleanName = name.replace('Action', '').trim();
            const option = document.createElement('option');
            option.value = name;
            option.text = cleanName;
            baseObjectSelect.appendChild(option);
        });
    });

    // Når et base objekt vælges, opdater det skjulte input og afspil animationen
    baseObjectSelect.addEventListener('change', (event) => {
        selectedBaseObjectInput.value = event.target.value;
        
        // Afspil den valgte animation for at visualisere delen
        modelViewer.animationName = event.target.value;
        if (event.target.value) {
            modelViewer.play();
        }
    });

    // Håndter formularindsendelse med AJAX og korrekt HTTPS-URL
    form.addEventListener('submit', (event) => {
    event.preventDefault(); // Forhindr standardindsendelse

    const formData = new FormData(form);
    const url = form.getAttribute('action'); // Hent URL fra formularens action-attribut
    
    console.log('Sending to:', url); // Debugging

    fetch(url, {
    method: 'POST',
    body: formData,
    headers: {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    }
    })
    .then(response => {
        if (!response.ok) throw new Error('Network response was not OK');
        return response.json();
    })
    .then(data => {
        console.log('Parsed JSON:', data);
    
        if (data.success && data.redirect) {
           window.location.href = data.redirect; // Manually redirect
    }   else {
           alert('Fejl: ' + data.error);
        }
    })
    .catch(error => {
           console.error('Fejl ved indsendelse:', error);
           alert('Der opstod en fejl: ' + error.message);
    });
});
</script>
@endsection
