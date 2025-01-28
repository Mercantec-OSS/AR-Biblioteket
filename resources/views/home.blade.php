@extends('layouts.main')

@section('title', 'Forside')

@section('content')
<div class="container">
    <div class="sidebar">
        <div class="filter-section">
            <h3>Filtrer Modeller</h3>
            <div class="filter-content">
                <select id="educationFilter" class="filter-select">
                    <option value="">Alle uddannelser</option>
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
        </div>
    </div>
    <div class="content">
        <ul class="model-list">
            @foreach($models as $index => $model)
                <li class="model-item" style="--i: {{ $index }};">
                    <a href="/model/{{ $model->id }}" class="model-link">
                        <div>
                            <span class="model-title">{{ $model->title }}</span>
                            <div style="color: #64748b; font-size: 0.9rem; margin-top: 0.25rem;">
                                <span>{{ $model->education }}</span>
                            </div>
                        </div>
                        <span class="arrow-icon">→</span>
                    </a>
                </li>
            @endforeach

            @if($models->isEmpty())
                <li class="model-item">
                    <div class="model-link">
                        <span>Ingen modeller fundet</span>
                    </div>
                </li>
            @endif
        </ul>
    </div>
</div>
@endsection 