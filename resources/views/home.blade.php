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
                    @foreach($educations as $education)
                        <option value="{{ $education->id }}">{{ $education->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="content">
        <ul class="model-list">
            @foreach($models as $index => $model)
                <li class="model-item" style="--i: {{ $index }};">
                    <div class="model-link">
                        <a href="/model/{{ $model->id }}" class="model-title-link">
                            <div class="model-content-wrapper">
                                <img src="{{ asset('storage/' . $model->image_path) }}" alt="{{ $model->title }}" class="model-thumbnail" />
                                <div class="model-info">
                                    <span class="model-title">{{ $model->title }}</span>
                                    <div class="education-tags-wrapper">
                                        @foreach($model->educations as $education)
                                            <span class="education-tag" data-education-id="{{ $education->id }}">
                                                {{ $education->title }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </a>
                        <div class="model-actions">
                            @if ($isAuthenticated)
                                <a href="/edit_model/{{ $model->id }}" class="edit-button">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                            @endif
                            <span class="arrow-icon">→</span>
                        </div>
                    </div>
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

@push('scripts')
<script src="{{ asset('js/filter.js') }}"></script>
@endpush
@endsection 