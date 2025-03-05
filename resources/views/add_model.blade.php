@extends('layouts.main')

@section('title', 'Tilføj 3D Model')

@section('content')
<div class="page-container">
    <div class="form-container">
        <h1 class="page-title">Tilføj 3D Model</h1>
        
        @if ($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        <form class="model-form" method="POST" action="{{ secure_url('/add_model') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="title">Titel</label>
                <input id="title" name="titleCreate" type="text" value="{{ old('titleCreate') }}" placeholder="Indtast titel" required/>
            </div>
            
            <div class="form-group">
                <label for="education">Uddannelse</label>
                <div class="education-selection-container">
                    <div class="education-tags-container" id="selectedEducations">
                        <div class="tags-placeholder">Vælg uddannelser</div>
                    </div>
                    <div class="education-dropdown-container">
                        <button type="button" class="dropdown-trigger" id="educationDropdownTrigger">
                            <span>Vælg uddannelse</span>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div class="education-dropdown" id="educationDropdown">
                            <div class="dropdown-options">
                                @foreach($educations as $education)
                                    <div class="dropdown-option" data-value="{{ $education->id }}">
                                        {{ $education->title }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <select id="education" name="educationCreate[]" multiple required style="display: none;">
                            @foreach($educations as $education)
                                <option value="{{ $education->id }}">{{ $education->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="description">Beskrivelse</label>
                <textarea id="description" name="descriptionCreate" placeholder="Indtast beskrivelse" rows="4" required>{{ old('descriptionCreate') }}</textarea>
            </div>
            
            <div class="form-group">
                <label for="model">3D Model</label>
                <div class="file-input-container">
                    <div class="file-input-wrapper">
                        <input id="model" name="modelCreate" type="file" required class="file-input" accept=".glb,.gltf,.obj,.stl,.fbx,.ply,.3ds,.mtl"/>
                        <div class="file-input-content">
                            <div class="file-input-text">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="upload-icon">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                <span class="file-input-prompt" id="modelPrompt">Vælg 3D model eller træk fil hertil</span>
                            </div>
                            <span class="file-input-formats">.glb, .gltf, .obj, .stl, .fbx, .ply, .3ds, .mtl</span>
                        </div>
                    </div>
                </div>
                <div id="mtlFileContainer" class="file-input-container" style="display: none; margin-top: 10px;">
                    <div class="file-input-wrapper">
                        <input id="mtlFile" name="mtlFile" type="file" class="file-input" accept=".mtl"/>
                        <div class="file-input-content">
                            <div class="file-input-text">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="upload-icon">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                <span class="file-input-prompt" id="mtlPrompt">Vælg MTL fil eller træk fil hertil</span>
                            </div>
                            <span class="file-input-formats">.mtl</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="image">Billede</label>
                <div class="file-input-container">
                    <div class="file-input-wrapper">
                        <input id="image" name="imageCreate" type="file" required class="file-input" accept="image/*"/>
                        <div class="file-input-content">
                            <div class="file-input-text">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="upload-icon">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="file-input-prompt" id="imagePrompt">Vælg billede eller træk fil hertil</span>
                            </div>
                            <span class="file-input-formats">JPG, PNG, GIF</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit">Tilføj</button>
            </div>
        </form>
    </div>
</div>

<!-- Conversion Progress Modal -->
<div id="conversionModal" class="modal">
    <div class="modal-content conversion-modal">
        <div class="modal-header">
            <div class="modal-title">
                <svg class="info-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h2>Konvertering i Gang</h2>
            </div>
            <div class="progress-container">
                <div class="progress-bar">
                    <div class="progress-indicator"></div>
                </div>
            </div>
        </div>
        <div class="modal-body">
            <div class="conversion-info">
                <div class="file-type">
                    <span class="label">Fil Type:</span>
                    <span id="fileTypeText" class="value"></span>
                </div>
                <div class="conversion-status">
                    <div class="status-icon">
                        <div class="spinner"></div>
                    </div>
                    <div class="status-text">
                        <p class="primary-text">Konverterer til GLB format</p>
                        <p class="secondary-text">Dette kan tage et øjeblik...</p>
                    </div>
                </div>
            </div>
            <div class="warning-container">
                <svg class="warning-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <p>Luk ikke siden ned før konverteringen er færdig</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropdownTrigger = document.getElementById('educationDropdownTrigger');
    const dropdown = document.getElementById('educationDropdown');
    const select = document.getElementById('education');
    const selectedContainer = document.getElementById('selectedEducations');
    const tagsPlaceholder = selectedContainer.querySelector('.tags-placeholder');
    const form = document.querySelector('.model-form');
    const modal = document.getElementById('conversionModal');
    const fileTypeText = document.getElementById('fileTypeText');
    const modelInput = document.getElementById('model');
    const mtlFileContainer = document.getElementById('mtlFileContainer');
    const mtlInput = document.getElementById('mtlFile');
    const mtlPrompt = document.getElementById('mtlPrompt');

    // Tjek om alle nødvendige elementer er tilgængelige
    if (!mtlPrompt) {
        console.error('mtlPrompt element not found');
        return;
    }

    function updateSelectedEducations() {
        const selectedOptions = Array.from(select.selectedOptions);
        selectedContainer.innerHTML = '';
        
        if (selectedOptions.length === 0) {
            selectedContainer.appendChild(tagsPlaceholder.cloneNode(true));
        } else {
            selectedOptions.forEach(option => {
                const tag = document.createElement('span');
                tag.className = 'education-tag';
                tag.innerHTML = `
                    ${option.text}
                    <button type="button" class="remove-tag" data-value="${option.value}">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                `;
                selectedContainer.appendChild(tag);
            });
        }
    }

    dropdownTrigger.addEventListener('click', function(e) {
        dropdown.classList.toggle('active');
        dropdownTrigger.classList.toggle('active');
        e.stopPropagation();
    });

    dropdown.addEventListener('click', function(e) {
        const option = e.target.closest('.dropdown-option');
        if (option) {
            const value = option.dataset.value;
            const selectOption = select.querySelector(`option[value="${value}"]`);
            selectOption.selected = !selectOption.selected;
            option.classList.toggle('selected');
            updateSelectedEducations();
        }
    });

    selectedContainer.addEventListener('click', function(e) {
        const removeButton = e.target.closest('.remove-tag');
        if (removeButton) {
            const value = removeButton.dataset.value;
            const selectOption = select.querySelector(`option[value="${value}"]`);
            const dropdownOption = dropdown.querySelector(`.dropdown-option[data-value="${value}"]`);
            selectOption.selected = false;
            dropdownOption.classList.remove('selected');
            updateSelectedEducations();
        }
    });

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.education-dropdown-container')) {
            dropdown.classList.remove('active');
            dropdownTrigger.classList.remove('active');
        }
    });

    updateSelectedEducations();

    // Håndter fil inputs
    const fileInputs = [
        { id: 'model', prompt: document.getElementById('modelPrompt') },
        { id: 'image', prompt: document.getElementById('imagePrompt') },
        { id: 'mtlFile', prompt: mtlPrompt }
    ];

    fileInputs.forEach(({ id, prompt }) => {
        const input = document.getElementById(id);
        const container = input.closest('.file-input-container');

        if (!input || !prompt || !container) {
            console.error(`Missing elements for ${id}`);
            return;
        }

        input.addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name;
            if (fileName) {
                prompt.textContent = fileName;
                container.classList.add('has-file');
            } else {
                prompt.textContent = id === 'model' 
                    ? 'Vælg 3D model eller træk fil hertil'
                    : id === 'mtlFile'
                        ? 'Vælg MTL fil eller træk fil hertil'
                        : 'Vælg billede eller træk fil hertil';
                container.classList.remove('has-file');
            }
        });

        container.addEventListener('dragover', function(e) {
            e.preventDefault();
            container.classList.add('dragover');
        });

        container.addEventListener('dragleave', function(e) {
            e.preventDefault();
            container.classList.remove('dragover');
        });

        container.addEventListener('drop', function(e) {
            e.preventDefault();
            container.classList.remove('dragover');
            const files = e.dataTransfer.files;
            if (files.length) {
                input.files = files;
                const event = new Event('change');
                input.dispatchEvent(event);
            }
        });
    });

    // Vis/skjul MTL input baseret på valgt filtype
    modelInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const extension = file.name.split('.').pop().toLowerCase();
            if (extension === 'obj') {
                mtlFileContainer.style.display = 'block';
            } else {
                mtlFileContainer.style.display = 'none';
            }
        }
    });

    // Håndter formularindsendelse
    form.addEventListener('submit', function(e) {
        const modelFile = modelInput.files[0];
        const mtlFile = mtlInput.files[0];

        if (!modelFile) {
            e.preventDefault();
            alert('Vælg venligst en model-fil');
            return;
        }

        const extension = modelFile.name.split('.').pop().toLowerCase();
        if (extension === 'obj' && !mtlFile) {
            e.preventDefault();
            alert('For OBJ filer skal du også uploade en MTL fil');
            return;
        }

        if (extension !== 'glb') {
            e.preventDefault();
            fileTypeText.textContent = extension.toUpperCase();
            modal.classList.add('show');
            modal.classList.add('visible');
            document.body.style.overflow = 'hidden';

            setTimeout(() => {
                form.action = 'https://ar-biblioteket.test/add_model';
                form.submit();
            }, 500);
        }
    });
});
</script>
@endpush

@endsection
