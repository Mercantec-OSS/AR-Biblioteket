@extends('layouts.main')

@section('title', 'Edit About AR Library')

@section('content')
<div class="container my-5">
    <h1>Edit About AR Library</h1>

    <form action="{{ route('about.update') }}" method="POST">
        @csrf
        <div class="mb-3">
            <textarea name="content" class="form-control" rows="10">{{ $about->content ?? '' }}</textarea>
        </div>
        <button type="submit" class="btn btn-success">Save</button>
    </form>
</div>
@endsection