@extends('layouts.main')

@section('title', 'Rediger 3D Model')

@push('styles')
<style>
/* Din eksisterende CSS forbliver uændret */
.bin-button {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 55px;
    height: 55px;
    border-radius: 50%;
    background-color: rgb(255, 95, 95);
    cursor: pointer;
    border: 2px solid rgb(255, 201, 201);
    transition-duration: 0.3s;
    position: relative;
    overflow: hidden;
    margin-right: -0.5rem;
}

.bin-bottom {
    width: 15px;
    z-index: 2;
}

.bin-top {
    width: 17px;
    transform-origin: right;
    transition-duration: 0.3s;
    z-index: 2;
}

.bin-button:hover .bin-top {
    transform: rotate(45deg);
}

.bin-button:hover {
    background-color: rgb(255, 0, 0);
}

.bin-button:active {
    transform: scale(0.9);
}

.garbage {
    position: absolute;
    width: 14px;
    height: auto;
    z-index: 1;
    opacity: 0;
    transition: all 0.3s;
}

.bin-button:hover .garbage {
    animation: throw 0.4s linear;
}

@keyframes throw {
    from {
        transform: translate(-400%, -700%);
        opacity: 0;
    }
    to {
        transform: translate(0%, 0%);
        opacity: 1;
    }
}

@media (max-width: 640px) {
    .bin-button {
        width: 45px;
        height: 45px;
    }
    
    .bin-bottom {
        width: 12px;
    }
    
    .bin-top {
        width: 14px;
    }
    
    .garbage {
        width: 11px;
    }
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #E5E7EB;
    position: relative;
}

.page-header .page-title {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 600;
    color: #0E38AB;
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    text-align: center;
    width: auto;
}

.page-header::before {
    content: "";
    width: 55px;
    height: 55px;
    visibility: hidden;
}

.conversion-modal {
    max-width: 500px;
    border-radius: 16px;
    background: #ffffff;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

.conversion-modal .modal-header {
    padding: 1.5rem;
    border-bottom: 1px solid #e5e7eb;
}

.modal-title {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
}

.modal-title h2 {
    font-size: 1.5rem;
    color: #111827;
    font-weight: 600;
    margin: 0;
}

.progress-container {
    margin-top: 1rem;
}

.progress-bar {
    height: 6px;
    background: #e5e7eb;
    border-radius: 3px;
    overflow: hidden;
}

.progress-indicator {
    height: 100%;
    width: 30%;
    background: #0E38AB;
    border-radius: 3px;
    animation: progress 2s infinite;
    transform-origin: left;
}

@keyframes progress {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(400%); }
}

.modal-body {
    padding: 1.5rem;
}

.conversion-info {
    background: #f3f4f6;
    border-radius: 12px;
    padding: 1.25rem;
    margin-bottom: 1.5rem;
}

.file-type {
    margin-bottom: 1rem;
}

.file-type .label {
    color: #6b7280;
    font-size: 0.875rem;
}

.file-type .value {
    font-weight: 600;
    color: #111827;
    margin-left: 0.5rem;
}

.conversion-status {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.status-icon .spinner {
    width: 32px;
    height: 32px;
    border: 3px solid #e5e7eb;
    border-top: 3px solid #0E38AB;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.info-icon {
    width: 2rem;
    height: 2rem;
    color: #0E38AB;
    margin-right: 0.5rem;
}

.status-text .primary-text {
    color: #111827;
    font-weight: 500;
    margin: 0;
}

.status-text .secondary-text {
    color: #6b7280;
    font-size: 0.875rem;
    margin: 0.25rem 0 0 0;
}

.warning-container {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    background: #fff4ed;
    border-radius: 8px;
    color: #9a3412;
}

.warning-container .warning-icon {
    width: 20px;
    height: 20px;
    color: #ea580c;
}

.warning-container p {
    margin: 0;
    font-size: 0.875rem;
    font-weight: 500;
}
</style>
@endpush

@section('content')
<div class="page-container">
    <div class="form-container">
        <div style="display: flex; justify-content: flex-end; margin-bottom: 1rem;">
            <button class="bin-button" onclick="showDeleteConfirmation()" aria-label="Slet model">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 39 7" class="bin-top">
                    <line stroke-width="4" stroke="white" y2="5" x2="39" y1="5"></line>
                    <line stroke-width="3" stroke="white" y2="1.5" x2="26.0357" y1="1.5" x1="12"></line>
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 33 39" class="bin-bottom">
                    <mask fill="white" id="path-1-inside-1_8_19">
                        <path d="M0 0H33V35C33 37.2091 31.2091 39 29 39H4C1.79086 39 0 37.2091 0 35V0Z"></path>
                    </mask>
                    <path mask="url(#path-1-inside-1_8_19)" fill="white" d="M0 0H33H0ZM37 35C37 39.4183 33.4183 43 29 43H4C-0.418278 43 -4 39.4183 -4 35H4H29H37ZM4 43C-0.418278 43 -4 39.4183 -4 35V0H4V35V43ZM37 0V35C37 39.4183 33.4183 43 29 43V35V0H37Z"></path>
                    <path stroke-width="4" stroke="white" d="M12 6L12 29"></path>
                    <path stroke-width="4" stroke="white" d="M21 6V29"></path>
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 89 80" class="garbage">
                    <path fill="white" d="M20.5 10.5L37.5 15.5L42.5 11.5L51.5 12.5L68.75 0L72 11.5L79.5 12.5H88.5L87 22L68.75 31.5L75.5066 25L86 26L87 35.5L77.5 48L70.5 49.5L80 50L77.5 71.5L63.5 58.5L53.5 68.5L65.5 70.5L45.5 73L35.5 79.5L28 67L16 63L12 51.5L0 48L16 25L22.5 17L20.5 10.5Z"></path>
                </svg>
            </button>
        </div>
        <h1 class="page-title">Rediger 3D Model</h1>
        
        @if ($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="model-form" method="POST" action="{{ secure_url(route('models.update', $model->id)) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="title">Titel</label>
                <input id="title" name="titleCreate" type="text" value="{{ $model->title }}" placeholder="Indtast titel" required/>
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
                                    <div class="dropdown-option" data-value="{{ $education->id }}" 
                                        {{ in_array($education->id, $model->educations->pluck('id')->toArray()) ? 'data-selected="true"' : '' }}>
                                        {{ $education->title }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <select id="education" name="educationCreate[]" multiple required style="display: none;">
                            @foreach($educations as $education)
                                <option value="{{ $education->id }}" 
                                    {{ in_array($education->id, $model->educations->pluck('id')->toArray()) ? 'selected' : '' }}>
                                    {{ $education->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="description">Beskrivelse</label>
                <textarea id="description" name="descriptionCreate" placeholder="Indtast beskrivelse" rows="4" required>{{ $model->description }}</textarea>
            </div>
            
            <div class="form-group">
                <label for="model">3D Model (Nuværende: {{ basename($model->model_path) }})</label>
                <div class="file-input-container">
                    <div class="file-input-wrapper">
                        <input id="model" name="modelCreate" type="file" class="file-input" accept=".glb,.gltf,.obj,.stl,.fbx,.ply,.3ds"/>
                        <div class="file-input-content">
                            <div class="file-input-text">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="upload-icon">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                <span class="file-input-prompt" id="modelPrompt">{{ basename($model->model_path) }}</span>
                            </div>
                            <span class="file-input-formats">.glb, .gltf, .obj, .stl, .fbx, .ply, .3ds</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="image">Billede (Nuværende: {{ basename($model->image_path) }})</label>
                <div class="file-input-container">
                    <div class="file-input-wrapper">
                        <input id="image" name="imageCreate" type="file" class="file-input" accept="image/*"/>
                        <div class="file-input-content">
                            <div class="file-input-text">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="upload-icon">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="file-input-prompt" id="imagePrompt">{{ basename($model->image_path) }}</span>
                            </div>
                            <span class="file-input-formats">JPG, PNG, GIF</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" id="submitButton">Gem Ændringer</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <svg class="warning-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <h2>Slet Model</h2>
        </div>
        <p>Er du sikker på, at du vil slette denne model?</p>
        <p class="warning-text">Dette kan ikke fortrydes.</p>
        <div class="modal-actions">
            <button onclick="cancelDelete()" class="cancel-button">Annuller</button>
            <form action="{{ secure_url('/model/' . $model->id) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="confirm-delete-button">Slet</button>
            </form>
        </div>
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

<script>
function showDeleteConfirmation() {
    const modal = document.getElementById('deleteModal');
    modal.classList.add('show');
    requestAnimationFrame(() => {
        modal.classList.add('visible');
        document.body.style.overflow = 'hidden';
    });
}

function cancelDelete() {
    const modal = document.getElementById('deleteModal');
    modal.classList.remove('visible');
    document.body.style.overflow = '';
    modal.addEventListener('transitionend', function handler() {
        modal.classList.remove('show');
        modal.removeEventListener('transitionend', handler);
    });
}

document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        cancelDelete();
    }
});
</script>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Education dropdown logic
    const dropdownTrigger = document.getElementById('educationDropdownTrigger');
    const dropdown = document.getElementById('educationDropdown');
    const select = document.getElementById('education');
    const selectedContainer = document.getElementById('selectedEducations');
    const tagsPlaceholder = selectedContainer.querySelector('.tags-placeholder');
    
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

    // Initialize selected options and tags
    document.querySelectorAll('.dropdown-option[data-selected="true"]').forEach(option => {
        option.classList.add('selected');
    });
    updateSelectedEducations();

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

    // File input handling
    ['model', 'image'].forEach(inputId => {
        const input = document.getElementById(inputId);
        const prompt = document.getElementById(`${inputId}Prompt`);
        const container = input.closest('.file-input-container');

        input.addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name;
            if (fileName) {
                prompt.textContent = fileName;
                container.classList.add('has-file');
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

    // Form submission with HTTPS enforcement
    const form = document.querySelector('.model-form');
    form.addEventListener('submit', function(e) {
        const modelInput = document.getElementById('model');
        const file = modelInput.files[0];
        
        if (file) {
            const extension = file.name.split('.').pop().toLowerCase();
            if (extension !== 'glb') {
                e.preventDefault(); // Forhindr standardindsendelse
                
                const modal = document.getElementById('conversionModal');
                const fileTypeText = document.getElementById('fileTypeText');
                fileTypeText.textContent = extension.toUpperCase();
                
                modal.classList.add('show');
                modal.classList.add('visible');
                document.body.style.overflow = 'hidden';

                // Send formularen som POST med PUT-metode efter en kort forsinkelse
                setTimeout(() => {
                    form.action = 'https://arbibliotek.socdata.dk/models/{{ $model->id }}'; // Tving HTTPS
                    form.submit(); // Udfør standardindsendelse
                }, 500);
            }
        }
        // Hvis ingen fil er valgt, fortsæt med standardindsendelse, men tving HTTPS
        form.action = 'https://arbibliotek.socdata.dk/models/{{ $model->id }}';
    });
});
</script>
@endpush
@endsection
