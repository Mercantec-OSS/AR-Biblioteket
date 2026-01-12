@extends('layouts.main')

@section('title', 'About AR Library')

@section('content')
<div class="container my-5">
    <h1>About AR Library</h1>

    <!-- Debug: check if logged in -->
    <p>{{ auth()->check() ? '✅ Logged in' : '❌ Not logged in' }}</p>

    <div class="mt-3">
        {!! $about->content ?? 'No information yet.' !!}
    </div>

    @auth
        @if(auth()->user()->admin == 1) <!-- only show button if admin = 1 -->
            <div class="mt-4">
                <a href="{{ route('about.edit') }}" class="btn btn-primary">
                    Edit About Page
                </a>
            </div>
        @endif
    @endauth
</div>
@endsection


