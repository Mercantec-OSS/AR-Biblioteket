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
                    <a href="/add_model">Tilføj Model</a>
                    <a href="/login">Log Ind</a>
                    <a href="/createUser">Opret Konto</a>
                    <!-- Logout link dynamically added -->
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

            // Handle logout logic
            const logoutLink = document.createElement('a');
            logoutLink.href = "#";
            logoutLink.textContent = "Log Ud";
            logoutLink.addEventListener('click', function(e) {
                e.preventDefault();
                localStorage.removeItem('authToken'); // Clear token on logout
                alert('You have been logged out.');
                window.location.href = '/login'; // Redirect to login page
            });

            dropdownMenu.appendChild(logoutLink);
        });

        // Function to make authenticated API requests
        async function makeAuthenticatedRequest(url, method = 'GET', body = null) {
            const token = localStorage.getItem('authToken'); // Retrieve token

            if (!token) {
                alert('You must be logged in to access this feature.');
                window.location.href = '/login';
                return;
            }

            const headers = {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
            };

            const options = {
                method,
                headers,
            };

            if (body) {
                options.body = JSON.stringify(body);
            }

            try {
                const response = await fetch(url, options);
                if (response.status === 401) {
                    alert('Unauthorized! Please log in again.');
                    localStorage.removeItem('authToken');
                    window.location.href = '/login';
                }
                return await response.json();
            } catch (error) {
                console.error('Error making authenticated request:', error);
            }
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
