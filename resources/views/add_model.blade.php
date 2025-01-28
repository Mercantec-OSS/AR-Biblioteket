@extends('layouts.main')

@section('title', 'Tilføj 3D Model')

@section('content')
<div class="page-container">
    <div class="form-container">
        <h1 class="page-title">Tilføj 3D Model</h1>
        
        <form class="model-form" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="title">Titel</label>
                <input id="title" name="titleCreate" type="text" placeholder="Indtast titel" required/>
            </div>
            
            <div class="form-group">
                <label for="education">Uddannelse</label>
                <select id="education" name="educationCreate" required>
                    <option value="">Vælg uddannelse</option>
                    <option value="Auto">Auto</option>
                    <option value="Automatik">Automatik</option>
                    <option value="Business">Business</option>
                    <option value="Data">Data</option>
                    <option value="Elektriker">Elektriker</option>
                    <option value="Elektronik">Elektronik</option>
                    <option value="Gastronomi">Gastronomi</option>
                    <option value="Industriteknik">Industriteknik</option>
                    <option value="Operatør">Operatør</option>
                    <option value="Produktør">Produktør</option>
                    <option value="Smed">Smed</option>
                    <option value="Struktør">Struktør</option>
                    <option value="Tømrer">Tømrer</option>
                    <option value="VVS">VVS</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="description">Beskrivelse</label>
                <textarea id="description" name="descriptionCreate" placeholder="Indtast beskrivelse" rows="4" required></textarea>
            </div>
            
            <div class="form-group">
                <label for="model">3D Model</label>
                <div class="file-input-wrapper">
                    <input id="model" name="modelCreate" type="file"/>
                </div>
            </div>
            
            <div class="form-group">
                <label for="image">Billede</label>
                <div class="file-input-wrapper">
                    <input id="image" name="imageCreate" type="file"/>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit">Tilføj</button>
            </div>
        </form>
    </div>
</div>
@endsection 