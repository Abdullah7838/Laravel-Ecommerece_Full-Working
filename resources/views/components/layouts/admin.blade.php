<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? 'Admin Dashboard - Wibzr' }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="bg-gray-100">
        <div class="min-h-screen flex">
            <!-- Sidebar -->
            <div class="w-64 bg-gray-800 text-white fixed inset-y-0 left-0 z-30 transform md:relative md:translate-x-0 transition duration-200 ease-in-out">
                <div class="flex items-center justify-center h-16 border-b border-gray-700">
                    <a href="{{ route('index') }}" class="text-xl font-bold">Wibzr Admin</a>
                </div>
                <nav class="mt-5">
                    <a href="/admin" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                        </svg>
                        Coupon Management
                    </a>
                    <a href="{{ route('index') }}" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Back to Store
                    </a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="flex-1 overflow-x-hidden overflow-y-auto">
                <!-- Top Navigation -->
                <header class="bg-white shadow-sm z-10">
                    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                        <h1 class="text-lg font-semibold text-gray-900">{{ $header ?? 'Dashboard' }}</h1>
                        <div class="flex items-center">
                            @auth
                                <span class="text-gray-700 mr-4">{{ Auth::user()->name }}</span>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="text-sm text-gray-700 hover:text-gray-900">
                                        Logout
                                    </button>
                                </form>
                            @endauth
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                    {{ $slot }}
                </main>
            </div>
        </div>

        @livewireScripts

        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <x-livewire-alert::scripts />
    </body>
</html>