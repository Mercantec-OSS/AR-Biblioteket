@extends('layouts.main')

@section('title', 'Forside')

@section('content')
<div class="container">
    <div class="sidebar">
        <div class="filter-section">
            <h3><i class="fas fa-filter"></i> Filtrer Modeller</h3>
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
                <li class="model-item" style="--i: {{ $index }};" data-url="/model/{{ $model->id }}">
                    <div class="model-link">
                        <a href="/model/{{ $model->id }}" class="model-title-link">
                            <div class="model-content-wrapper">
                                <img src="{{ secure_asset('storage/' . $model->image_path) }}" alt="{{ $model->title }}" class="model-thumbnail" />
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
                            @if ($isAuthenticated && (auth()->id() === $model->user_id || auth()->user()->admin))
                                <a href="/edit_model/{{ $model->id }}" class="edit-button">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                            @endif
                            <span class="arrow-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-4.146-4.146a.5.5 0 1 1 .708-.708l5 5a.5.5 0 0 1 0 .708l-5 5a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                                </svg>
                            </span>
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

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
    /* Generelle stilarter */
    * {
        font-family: 'Roboto', sans-serif;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        background-color: #F5F5F5;
        display: flex;
        gap: 20px;
    }

    .sidebar {
        flex: 1;
        min-width: 200px;
    }

    .content {
        flex: 3;
    }

    /* Filter-sektion */
    .filter-section {
        background: #fff;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .filter-section h3 {
        color: #1E90FF;
        font-size: 1.2rem;
        margin-bottom: 10px;
    }

    .filter-select {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 1rem;
    }

    /* Model-liste med Grid */
    .model-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        padding: 0;
    }

    .model-item {
        list-style: none;
        padding: 15px;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        cursor: pointer;
    }

    .model-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .model-link {
        display: flex;
        justify-content: space-between;
        align-items: center;
        text-decoration: none;
        color: #333;
    }

    .model-thumbnail {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 6px;
    }

    .model-info {
        margin-left: 12px;
        flex-grow: 1;
    }

    .model-title {
        font-size: 1.1rem;
        font-weight: 500;
        color: #1E90FF;
    }

    .education-tags-wrapper {
        margin-top: 6px;
    }

    .education-tag {
        background-color: #1E90FF;
        color: #fff;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        margin-right: 4px;
        display: inline-block;
    }

    /* Knapper og ikoner */
    .edit-button {
        padding: 6px;
        border-radius: 4px;
        background: #1E90FF;
        color: #fff;
        transition: background 0.3s ease, box-shadow 0.3s ease;
    }

    .edit-button:hover {
        background: #1677D9; /* Lidt mørkere blå */
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .edit-button svg {
        stroke: #fff;
    }

    .arrow-icon {
        display: inline-block;
        margin-left: 8px;
        font-size: 16px;
        color: #FFA500;
        transition: transform 0.3s ease;
    }

    .model-link:hover .arrow-icon {
        transform: translateX(4px);
    }

    /* Responsivitet */
    @media (max-width: 768px) {
        .container {
            flex-direction: column;
        }

        .model-list {
            grid-template-columns: 1fr;
        }

        .arrow-icon {
            display: inline-block;
        }
    }
</style>
@endpush

@push('scripts')
<script src="{{ secure_asset('js/filter.js') }}"></script>
<script>
    document.querySelectorAll('.model-item').forEach(function(item) {
        item.addEventListener('click', function() {
            const url = item.getAttribute('data-url');
            window.location.href = url;
        });
    });
</script>
@endpush

@endsection
