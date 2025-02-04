@extends('layouts.main')
 
@section('title', 'Log Ind')
 
@section('content')
<title>Login</title>
<body>
<div class="login-box">
    <form action="/login" method="post">
    @csrf
        <h2>Log Ind</h2>
        <div class="user-box">
            <input required type="text" name="emailLogin" id="emailLogin" placeholder="">
            <label for="emailLogin">Email</label>
        </div>
        <div class="user-box">
            <input required type="password" name="passwordLogin" id="passwordLogin" placeholder="">
            <label for="passwordLogin">Adgangskode</label>
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