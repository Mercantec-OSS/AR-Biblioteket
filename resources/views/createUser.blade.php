@extends('layouts.main')

@section('title', 'Opret Konto')

@section('content')
<div class="login-box">
    @if ($errors->has('token'))
        <div class="alert alert-error">
            <div class="error-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12" y2="16"></line>
                </svg>
            </div>
            <div class="error-message">
                <h4>Ugyldig Token</h4>
                <p>{{ $errors->first('token') }}</p>
            </div>
        </div>
    @endif

    <form action="{{ route('register') }}" method="post">
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
        <div class="user-box">
            <input required type="text" name="token" id="token" placeholder="">
            <label for="token">Token</label>
            <small class="token-info">Kontakt Kasper Holm Bonde Christiansen for at få din token</small>
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

<style>
.login-box {
    margin-top: 80px; 
    padding-bottom: 40px; 
}

.alert.alert-error {
    background-color: #fee2e2;
    border: 1px solid #ef4444;
    border-radius: 8px;
    padding: 1rem;
    margin: 1rem auto 2rem;
    max-width: 90%;
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    position: relative;
    z-index: 1;
}

.error-icon {
    flex-shrink: 0;
    color: #dc2626;
    width: 24px;
    height: 24px;
}

.error-message h4 {
    color: #991b1b;
    font-size: 1rem;
    font-weight: 600;
    margin: 0 0 0.25rem 0;
}

.error-message p {
    color: #7f1d1d;
    font-size: 0.875rem;
    margin: 0;
}

.token-info {
    display: block;
    color: #64748b;
    font-size: 0.8rem;
    margin-top: 0.5rem;
    text-align: left;
}

@media (max-width: 768px) {
    .login-box {
        margin-top: 60px;
        overflow-y: auto;
        max-height: calc(100vh - 60px);
        padding: 20px;
    }
}
</style>
@endsection
