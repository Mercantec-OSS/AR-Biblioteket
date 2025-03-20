@extends('layouts.main')

@section('title', 'Information')

@section('content')
    <div class="container">
        <h2>Information Page</h2>

        @if(auth()->user() && auth()->user()->admin) 
            <!-- Admin Editable Quill Editor -->
            <div id="editor-container">{!! $content !!}</div>
            <button id="save-button" class="btn btn-primary mt-3">Save</button>

            <form id="save-form" action="{{ route('info.update') }}" method="POST" style="display: none;">
                @csrf
                <input type="hidden" name="content" id="hidden-content">
            </form>
        @else
            <!-- Display content for regular users -->
            <div>{!! $content !!}</div>
        @endif
    </div>

    <script>
        @if(auth()->user() && auth()->user()->admin)
            var quill = new Quill('#editor-container', {
                theme: 'snow'
            });

            document.getElementById('save-button').addEventListener('click', function () {
                document.getElementById('hidden-content').value = quill.root.innerHTML;
                document.getElementById('save-form').submit();
            });
        @endif
    </script>
@endsection
