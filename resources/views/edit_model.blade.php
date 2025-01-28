@extends('layouts.main')

@section('title', 'Rediger 3D Model')

@section('content')
<div class="page-container">
    <div class="form-container">
        <h1 class="page-title">Rediger 3D Model</h1>
        
        <form class="model-form" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="title">Titel</label>
                <input id="title" name="title" type="text" placeholder="Indtast titel" required/>
            </div>
            
            <div class="form-group">
                <label for="education">Uddannelse</label>
                <select id="education" name="education" required>
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
                <textarea id="description" name="description" placeholder="Indtast beskrivelse" rows="4" required></textarea>
            </div>
            
            <div class="form-group">
                <label for="model">3D Model</label>
                <div class="file-input-wrapper">
                    <input id="model" name="model" type="file" />
                </div>
            </div>
            
            <div class="form-group">
                <label for="image">Billede</label>
                <div class="file-input-wrapper">
                    <input id="image" name="image" type="file"/>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit">Gem Ændringer</button>
            </div>
        </form>
    </div>
</div>
@endsection 