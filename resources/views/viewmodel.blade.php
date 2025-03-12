@extends('layouts.main')

@section('title', $model->title)

@section('head')
    <link rel="stylesheet" href="{{ asset('css/modelviewer.css') }}">
@endsection

@section('content')
<div class="model-viewer-container">
    <div class="page-header">
        <div class="header-top">
            <h1>{{ $model->title }}</h1>
            <button class="qr-button" onclick="toggleQRModal()">
                <i class="fas fa-qrcode"></i>
                <span>Vis QR-kode</span>
            </button>
        </div>
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

<!-- Tilføj modal i bunden af model-viewer-container -->
<div id="qrModal" class="qr-modal">
    <div class="qr-modal-content">
        <div class="qr-modal-header">
            <h3>Scan QR-kode</h3>
            <button class="close-button" onclick="toggleQRModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="qr-modal-body">
            <p>Scan denne QR-kode for at dele eller tilgå denne model på en anden enhed</p>
            <div id="qrcode"></div>
        </div>
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

    // Generate QR Code
    const currentUrl = window.location.href;
    new QRCode(document.getElementById("qrcode"), {
        text: currentUrl,
        width: 128,
        height: 128,
        colorDark: "#000000",
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.H
    });
});

function toggleQRModal() {
    const modal = document.getElementById('qrModal');
    modal.classList.toggle('active');
    
    // Generer QR kode første gang modalen åbnes
    if (modal.classList.contains('active') && !document.querySelector('#qrcode canvas')) {
        const currentUrl = window.location.href;
        new QRCode(document.getElementById("qrcode"), {
            text: currentUrl,
            width: 160,
            height: 160,
            colorDark: "#1e293b",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });
    }
}

// Luk modal når der klikkes udenfor
document.addEventListener('click', function(event) {
    const modal = document.getElementById('qrModal');
    const modalContent = document.querySelector('.qr-modal-content');
    const qrButton = document.querySelector('.qr-button');
    
    if (modal.classList.contains('active') && 
        !modalContent.contains(event.target) && 
        !qrButton.contains(event.target)) {
        modal.classList.remove('active');
    }
});
</script>
@endsection