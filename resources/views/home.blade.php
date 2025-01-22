@extends('layouts.main')

@section('title', 'Forside')

@section('content')
<div class="container">
    <div class="sidebar">
        <div class="filter-section">
            <h3>Filtrer Modeller</h3>
            <div class="filter-content">
                <p class="coming-soon">Kommer Snart!</p>
            </div>
        </div>
    </div>
    <div class="content">
        <ul class="model-list">
            @php
                $dummyModels = [ 
                    [
                        'id' => 1, 
                        'title' => '3D Model 1',
                        'education' => 'Elektriker'
                    ],
                    [
                        'id' => 2, 
                        'title' => '3D Model 2',
                        'education' => 'VVS'
                    ],
                    [
                        'id' => 3, 
                        'title' => '3D Model 3',
                        'education' => 'Gastronomi'
                    ],
                    [
                        'id' => 4, 
                        'title' => 'AR Model 1',
                        'education' => 'Auto'
                    ],
                    [
                        'id' => 5, 
                        'title' => 'AR Model 2',
                        'education' => 'Business'
                    ],
                ];
            @endphp

            @foreach($dummyModels as $index => $model)
                <li class="model-item" style="--i: {{ $index }};">
                    <a href="/model/{{ $model['id'] }}" class="model-link">
                        <div>
                            <span class="model-title">{{ $model['title'] }}</span>
                            <div style="color: #64748b; font-size: 0.9rem; margin-top: 0.25rem;">
                                <span>{{ $model['education'] }}</span>
                            </div>
                        </div>
                        <span class="arrow-icon">→</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection 