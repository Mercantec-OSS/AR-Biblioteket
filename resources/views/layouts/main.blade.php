<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <link href="{{ secure_asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ secure_asset('css/home.css') }}" rel="stylesheet">
    <link href="{{ secure_asset('css/navbar.css') }}" rel="stylesheet">
    <link href="{{ secure_asset('css/forms.css') }}" rel="stylesheet">
    <link href="{{ secure_asset('css/login.css') }}" rel="stylesheet">
    <link href="{{ secure_asset('css/viewmodel.css') }}" rel="stylesheet">
    <script type="module" src="https://unpkg.com/@google/model-viewer/dist/model-viewer.min.js"></script>
</head>
<body>
    <div class="page">
        <main>
            <div class="navbar">
                <span class="title"><a href="/" class="secure-link">AR Biblioteket</a></span>
                <button class="dropdown-toggle" aria-expanded="false">
                    <!-- Hamburger Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 24 24" width="24" height="24">
                        <path d="M3 6h18v2H3zm0 5h18v2H3zm0 5h18v2H3z"/>
                    </svg>
                </button>
                <nav class="dropdown-menu">
                    <a href="/" class="secure-link">Forside</a>

                    @if (auth()->check())
                        <a href="/add_model" class="secure-link">TilfÃ¸j Model</a>
                        <div class="logout-container">
                          <form action="https://arbibliotek.socdata.dk/logout" method="POST" style="display:inline;">
  			  @csrf
   			 <a href="#" onclick="event.preventDefault(); this.closest('form').submit();" class="secure-link">Log ud</a>
			</form>
                        </div>
                    @else
                        <a href="/login" class="secure-link">Log Ind</a>
                        <a href="/createUser" class="secure-link">Opret Konto</a>
                    @endif
                </nav>
            </div>

            <article class="content">
                @yield('content')
            </article>
        </main>
    </div>

    <!-- Optional: Handling the Dropdown Menu -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownToggle = document.querySelector('.dropdown-toggle');
            const dropdownMenu = document.querySelector('.dropdown-menu');

            dropdownToggle.addEventListener('click', function() {
                dropdownMenu.classList.toggle('active');
                const isExpanded = dropdownToggle.getAttribute('aria-expanded') === 'true' || false;
                dropdownToggle.setAttribute('aria-expanded', !isExpanded);
            });

            window.addEventListener('click', function(event) {
                if (!dropdownToggle.contains(event.target) && !dropdownMenu.contains(event.target)) {
                    dropdownMenu.classList.remove('active');
                    dropdownToggle.setAttribute('aria-expanded', 'false');
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>