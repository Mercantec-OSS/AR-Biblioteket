@extends('layouts.main')

@section('title', 'Rediger 3D Model')

@section('content')
<div class="page-container">
    <div class="form-container">
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

        <form class="model-form" method="POST" action="/edit_model/{{ $model->id }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="title">Titel</label>
                <input id="title" name="titleCreate" type="text" value="{{ $model->title }}" placeholder="Indtast titel" required/>
            </div>
            
            <div class="form-group">
                <label for="education">Uddannelse</label>
                <select id="education" name="educationCreate" required>
                    <option value="">Vælg uddannelse</option>
                    <option value="Auto" {{ $model->education == 'Auto' ? 'selected' : '' }}>Auto</option>
                    <option value="Automatik" {{ $model->education == 'Automatik' ? 'selected' : '' }}>Automatik</option>
                    <option value="Business" {{ $model->education == 'Business' ? 'selected' : '' }}>Business</option>
                    <option value="Data" {{ $model->education == 'Data' ? 'selected' : '' }}>Data</option>
                    <option value="Elektriker" {{ $model->education == 'Elektriker' ? 'selected' : '' }}>Elektriker</option>
                    <option value="Elektronik" {{ $model->education == 'Elektronik' ? 'selected' : '' }}>Elektronik</option>
                    <option value="Gastronomi" {{ $model->education == 'Gastronomi' ? 'selected' : '' }}>Gastronomi</option>
                    <option value="Industriteknik" {{ $model->education == 'Industriteknik' ? 'selected' : '' }}>Industriteknik</option>
                    <option value="Operatør" {{ $model->education == 'Operatør' ? 'selected' : '' }}>Operatør</option>
                    <option value="Produktør" {{ $model->education == 'Produktør' ? 'selected' : '' }}>Produktør</option>
                    <option value="Smed" {{ $model->education == 'Smed' ? 'selected' : '' }}>Smed</option>
                    <option value="Struktør" {{ $model->education == 'Struktør' ? 'selected' : '' }}>Struktør</option>
                    <option value="Tømrer" {{ $model->education == 'Tømrer' ? 'selected' : '' }}>Tømrer</option>
                    <option value="VVS" {{ $model->education == 'VVS' ? 'selected' : '' }}>VVS</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="description">Beskrivelse</label>
                <textarea id="description" name="descriptionCreate" placeholder="Indtast beskrivelse" rows="4" required>{{ $model->description }}</textarea>
            </div>
            
            <div class="form-group">
                <label for="model">3D Model (Nuværende: {{ basename($model->model_path) }})</label>
                <div class="file-input-wrapper">
                    <input id="model" name="modelCreate" type="file" />
                </div>
            </div>
            
            <div class="form-group">
                <label for="image">Billede (Nuværende: {{ basename($model->image_path) }})</label>
                <div class="file-input-wrapper">
                    <input id="image" name="imageCreate" type="file"/>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit">Gem Ændringer</button>
            </div>
        </form>

        <button type="button" class="bin-button" onclick="showDeleteConfirmation()" aria-label="Slet model">
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
            <form action="/model/{{ $model->id }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="confirm-delete-button">Slet</button>
            </form>
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

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        cancelDelete();
    }
});
</script>
@endsection 