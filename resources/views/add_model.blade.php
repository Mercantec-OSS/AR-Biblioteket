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

        <form class="model-form" method="post" enctype="multipart/form-data" action="/add_model">
            @csrf
            <div class="form-group">
                <label for="title">Titel</label>
                <input id="title" name="titleCreate" type="text" placeholder="Indtast titel" required/>
            </div>
            
            <div class="form-group">
                <label for="education">Uddannelse</label>
                <select id="education" name="educationCreate[]" multiple required>
                    <option value="">Vælg uddannelse</option>
                    @foreach($educations as $education)
                        <option value="{{ $education->id }}">{{ $education->title }}</option>
                    @endforeach
                </select>
            </div>                              
            
            <div class="form-group">
                <label for="description">Beskrivelse</label>
                <textarea id="description" name="descriptionCreate" placeholder="Indtast beskrivelse" rows="4" required></textarea>
            </div>
            
            <div class="form-group">
                <label for="model">3D Model</label>
                <div class="file-input-wrapper">
                    <input id="model" name="modelCreate" type="file" required/>
                </div>
            </div>
            
            <div class="form-group">
                <label for="image">Billede</label>
                <div class="file-input-wrapper">
                    <input id="image" name="imageCreate" type="file" required/>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit">Tilføj</button>
            </div>
        </form>
    </div>
</div>
@endsection
