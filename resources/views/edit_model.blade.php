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

        <button type="button" class="delete-button" onclick="showDeleteConfirmation()" aria-label="Slet model">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
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