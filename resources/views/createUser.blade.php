@extends('layouts.main')

@section('title', 'Opret Konto')

@section('content')
<div class="login-box">
    <form action="" method="post">
        @csrf
        <h2>Opret Konto</h2>
        <div class="user-box">
            <input required type="text" name="name" id="name" placeholder="">
            <label for="name">Navn</label>
        </div>
        <div class="user-box">
            <input required type="text" name="email" id="email" placeholder="">
            <label for="email">Email</label>
        </div>
        <div class="user-box">
            <input required type="password" name="password" id="password" placeholder="">
            <label for="password">Adgangskode</label>
        </div>
        <div class="user-box">
            <input required type="text" name="department" id="department" placeholder="">
            <label for="department">Afdeling</label>
        </div>
        <button type="submit">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            Opret Bruger
        </button>
    </form>
</div>
@endsection