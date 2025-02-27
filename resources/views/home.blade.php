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
                            @if ($isAuthenticated)
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
<style>
    .arrow-icon {
        display: inline-block;
        margin-left: 8px;
        font-size: 16px;
        transition: transform 0.3s ease;
        color: #333; /* Adjust color as needed */
    }

    /* Rotate arrow on hover */
    .model-link:hover .arrow-icon {
        transform: translateX(4px);
    }

    /* Make sure arrow is visible on all devices */
    @media (max-width: 768px) {
        .arrow-icon {
            display: inline-block;
        }
    }

    .model-item {
        list-style: none;
        margin-bottom: 20px;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 8px;
        cursor: pointer; /* Add cursor to indicate clickability */
    }

    .model-link {
        display: flex;
        justify-content: space-between;
        align-items: center;
        text-decoration: none;
        color: inherit;
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

    .education-tags-wrapper {
        margin-top: 6px;
    }

    .education-tag {
        background-color: #f1f1f1;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        margin-right: 4px;
        display: inline-block;
    }

    .edit-button svg {
        stroke: #555;
    }
</style>
@endpush

@push('scripts')
<script src="{{ secure_asset('js/filter.js') }}"></script>
<script>
    // JavaScript to make the entire model-item clickable
    document.querySelectorAll('.model-item').forEach(function(item) {
        item.addEventListener('click', function() {
            const url = item.getAttribute('data-url');
            window.location.href = url; // Navigate to the model's page
        });
    });
</script>
@endpush

@endsection
