@extends('layouts.main')

@section('content')
<div class="container">
    <h2>Admin Panel</h2>

    @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    @if(auth()->user())
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Department</th>
                    <th>Password</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <form action="{{ route('admin.updateUser', $user->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <td><input type="text" name="name" value="{{ $user->name }}" class="form-control"></td>
                            <td><input type="email" name="email" value="{{ $user->email }}" class="form-control"></td>
                            <td><input type="text" name="department" value="{{ $user->department }}" class="form-control"></td>
                            <td><input type="password" name="password" placeholder="New Password" class="form-control"></td>
                            <td>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </td>
                        </form>
                        <td>
                            <form action="{{ route('admin.deleteUser', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>You must be logged in to access the admin panel.</p>
    @endif
</div>
@endsection
