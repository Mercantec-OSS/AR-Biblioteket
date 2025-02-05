@extends('layouts.main')

@section('title', 'Log Ind')

@section('content')
<title>Login</title>
<body>
<div class="login-box">
    <form method="POST" action="{{ route('login') }}">
    @csrf
        <h2>Log Ind</h2>
        <div class="user-box">
            <input required type="text" name="email" id="email" placeholder="">
            <label for="email">Email</label>
        </div>
        <div class="user-box">
            <input required type="password" name="password" id="password" placeholder="">
            <label for="password">Adgangskode</label>
        </div>
        <button type="submit">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            Log Ind
        </button>
    </form>
    <p>Har du ikke en konto? <a href="/createUser" class="signup-link">Opret konto her!</a></p>
</div>
</body>
@endsection
