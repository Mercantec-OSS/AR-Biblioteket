<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/home.css') }}" rel="stylesheet">
    <link href="{{ asset('css/navbar.css') }}" rel="stylesheet">
    <link href="{{ asset('css/forms.css') }}" rel="stylesheet">
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
    <link href="{{ asset('css/viewmodel.css') }}" rel="stylesheet">
    <script type="module" src="https://unpkg.com/@google/model-viewer/dist/model-viewer.min.js"></script>
</head>
<body>
    <div class="page">
        <main>
            <div class="navbar">
                <span class="title">AR Biblioteket</span>
                <button class="dropdown-toggle" aria-expanded="false">
                    <!-- Hamburger Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 24 24" width="24" height="24">
                        <path d="M3 6h18v2H3zm0 5h18v2H3zm0 5h18v2H3z"/>
                    </svg>
                </button>
                <nav class="dropdown-menu">
                    <a href="/">Forside</a>
                    @if(app(App\Http\Controllers\JWTAuthController::class)->isAuthenticated(request()))
                        <a href="/add_model">Tilføj Model</a>
                        <a href="#" class="logout-link">Log ud</a>
                    @endauth
                    @guest('api')
                        <a href="/login">Log Ind</a>
                        <a href="/createUser">Opret Konto</a>
                    @endguest
                </nav>
            </div>

            <article class="content">
                @yield('content')
            </article>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownToggle = document.querySelector('.dropdown-toggle');
            const dropdownMenu = document.querySelector('.dropdown-menu');

            // Toggle dropdown menu on click
            dropdownToggle.addEventListener('click', function() {
                dropdownMenu.classList.toggle('active');
                const isExpanded = dropdownToggle.getAttribute('aria-expanded') === 'true' || false;
                dropdownToggle.setAttribute('aria-expanded', !isExpanded);
            });

            // Close the dropdown if clicked outside
            window.addEventListener('click', function(event) {
                if (!dropdownToggle.contains(event.target) && !dropdownMenu.contains(event.target)) {
                    dropdownMenu.classList.remove('active');
                    dropdownToggle.setAttribute('aria-expanded', 'false');
                }
            });

            // Handle logout
            const logoutLink = document.querySelector('.logout-link');
            if (logoutLink) {
                logoutLink.addEventListener('click', async function(e) {
                    e.preventDefault();
                    try {
                        const response = await fetch('/api/logout', {
                            method: 'POST',
                            credentials: 'same-origin',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        });
                        
                        if (response.ok) {
                            window.location.href = '/login';
                        }
                    } catch (error) {
                        console.error('Logout failed:', error);
                    }
                });
            }
        });

        // Update the authenticated request function
        async function makeAuthenticatedRequest(url, method = 'GET', body = null) {
            const options = {
                method,
                credentials: 'same-origin', // Important for cookies
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            };

            if (body) {
                options.body = JSON.stringify(body);
            }

            const response = await fetch(url, options);
            
            if (response.status === 401) {
                window.location.href = '/login';
                return;
            }
            
            return response.json();
        }

        // Example: Fetching a protected route
        async function fetchProtectedRoute() {
            const data = await makeAuthenticatedRequest('/protected');
            console.log(data);
        }

        // Call this function wherever you need to fetch protected data
        // fetchProtectedRoute();
    </script>
</body>
</html>
