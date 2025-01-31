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
                <span class="title"><a href="/">AR Biblioteket</a></span>
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
        });

        document.addEventListener('DOMContentLoaded', function() {
            const educationFilter = document.getElementById('educationFilter');
            const modelItems = document.querySelectorAll('.model-item');

            educationFilter.addEventListener('change', function() {
                const selectedEducation = this.value;
                let hasVisibleModels = false;

                modelItems.forEach(item => {
                    const educationSpan = item.querySelector('div[style*="color: #64748b"] span');
                    
                    if (!educationSpan) return;
                    
                    if (!selectedEducation || educationSpan.textContent.trim() === selectedEducation) {
                        item.style.display = '';
                        item.style.animation = 'fadeIn 0.5s ease-in-out forwards';
                        hasVisibleModels = true;
                    } else {
                        item.style.display = 'none';
                    }
                });

                // Show "No models found" message if no models are visible
                const noModelsMessage = document.querySelector('.no-models-message');
                if (!hasVisibleModels) {
                    if (!noModelsMessage) {
                        const message = document.createElement('li');
                        message.className = 'model-item no-models-message';
                        message.innerHTML = '<div class="model-link"><span>Ingen modeller fundet</span></div>';
                        document.querySelector('.model-list').appendChild(message);
                    }
                } else if (noModelsMessage) {
                    noModelsMessage.remove();
                }
            });
        });
    </script>
</body>
</html> 