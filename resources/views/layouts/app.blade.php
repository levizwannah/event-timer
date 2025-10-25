<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Event Time Planner' }}</title>
    <link rel="icon" href="{{ asset('event-timer-logo.png') }}" type="image/x-icon">

    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#1E40AF">
    <link rel="apple-touch-icon" href="{{ asset('/ios/192x192.png') }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Event Timer">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@500&display=swap');

        .font-digital {
            font-family: 'Orbitron', monospace;
        }

        .blink {
            animation: blink 1s step-start infinite;
        }

        @keyframes blink {
            50% {
                opacity: 0;
            }
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex flex-col bg-gray-50 text-gray-800 font-sans">

    <!-- Navbar -->
    <nav class="bg-blue-600 text-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-16">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="text-2xl font-semibold tracking-tight hover:opacity-90">
                Event<span class="text-blue-200">TimePlanner</span>
            </a>

            <!-- Desktop Menu -->
            <div class="hidden sm:flex space-x-6 text-sm font-medium">
                <a href="{{ route('home') }}"
                    class="hover:text-blue-200 transition-colors {{ request()->routeIs('home') ? 'text-blue-200 underline underline-offset-4' : '' }}">
                    Home
                </a>

                <a href="{{ route('programs.create') }}"
                    class="hover:text-blue-200 transition-colors {{ request()->routeIs('programs.create') ? 'text-blue-200 underline underline-offset-4' : '' }}">
                    Create Program
                </a>

                <a href="{{ route('programs.search') }}"
                    class="hover:text-blue-200 transition-colors {{ request()->routeIs('programs.search') ? 'text-blue-200 underline underline-offset-4' : '' }}">
                    Open Program
                </a>
            </div>

            <!-- Mobile Menu Button -->
            <div class="sm:hidden">
                <button id="mobileMenuButton" class="focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden sm:hidden bg-blue-700">
            <a href="{{ route('home') }}"
                class="block px-4 py-2 text-sm hover:bg-blue-600 {{ request()->routeIs('home') ? 'bg-blue-600' : '' }}">
                Home
            </a>

            <a href="{{ route('programs.create') }}"
                class="block px-4 py-2 text-sm hover:bg-blue-600 {{ request()->routeIs('programs.create') ? 'bg-blue-600' : '' }}">
                Create Program
            </a>

            <a href="{{ route('programs.search') }}"
                class="block px-4 py-2 text-sm hover:bg-blue-600 {{ request()->routeIs('programs.search') ? 'bg-blue-600' : '' }}">
                Open Program
            </a>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if (session('global-success') || session('global-error'))
        <div class="max-w-4xl mx-auto mt-6 px-4">
            @if (session('global-success'))
                <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-md relative mb-4"
                    role="alert">
                    <span class="block sm:inline font-medium">{{ session('global-success') }}</span>
                </div>
            @endif

            @if (session('global-error'))
                <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-md relative mb-4"
                    role="alert">
                    <span class="block sm:inline font-medium">{{ session('global-error') }}</span>
                </div>
            @endif
        </div>
    @endif

    <!-- Page Content -->
    <main class="flex-grow">
        <div class="max-w-4xl mx-auto">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t py-4 text-center text-sm text-gray-500">
        <p>
            © {{ date('Y') }} Event Time Planner — Plan. Time. Manage.
        </p>
    </footer>

    <!-- Mobile Menu Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const btn = document.getElementById('mobileMenuButton');
            const menu = document.getElementById('mobileMenu');
            btn.addEventListener('click', () => {
                menu.classList.toggle('hidden');
            });
        });
    </script>

    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/service-worker.js')
                    .then(function(registration) {
                        console.log('Service Worker registered with scope:', registration.scope);
                    })
                    .catch(function(error) {
                        console.log('Service Worker registration failed:', error);
                    });
            });
        }
    </script>


</body>

</html>
