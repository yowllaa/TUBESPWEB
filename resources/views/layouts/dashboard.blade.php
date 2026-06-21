<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Dashboard - {{ config('app.name', 'Layanan Darurat') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Leaflet Map CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Tailwind & Alpine (via Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="h-full text-gray-800" x-data="{ sidebarOpen: false }">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar for Mobile (Off-canvas) -->
        <div class="fixed inset-0 z-40 flex md:hidden" role="dialog" aria-modal="true" x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-gray-600 bg-opacity-75" aria-hidden="true" @click="sidebarOpen = false"></div>

            <!-- Sidebar Content -->
            <div class="relative flex flex-col flex-1 w-full max-w-xs pt-5 pb-4 bg-gray-900" x-show="sidebarOpen" x-transition:enter="transition ease-in-out duration-300 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full">
                <!-- Close Button -->
                <div class="absolute top-0 right-0 pt-2 -mr-12">
                    <button type="button" class="flex items-center justify-center w-10 h-10 ml-1 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" @click="sidebarOpen = false">
                        <span class="sr-only">Close sidebar</span>
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Brand -->
                <div class="flex items-center flex-shrink-0 px-4">
                    <div class="flex items-center space-x-2">
                        <div class="p-1.5 bg-red-600 rounded-lg text-white">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <span class="text-lg font-bold text-white tracking-wider">SIP-DARURAT</span>
                    </div>
                </div>

                <!-- Sidebar Navigation Items -->
                <div class="flex-1 h-0 mt-5 overflow-y-auto">
                    <nav class="px-2 space-y-1">
                        @include('layouts.sidebar-links')
                    </nav>
                </div>
            </div>
            <div class="flex-shrink-0 w-14" aria-hidden="true"></div>
        </div>

        <!-- Static Sidebar for Desktop -->
        <div class="hidden md:flex md:flex-shrink-0">
            <div class="flex flex-col w-64 bg-gray-900">
                <!-- Brand -->
                <div class="flex items-center h-16 px-4 bg-gray-950 border-b border-gray-800">
                    <div class="flex items-center space-x-2">
                        <div class="p-1.5 bg-red-600 rounded-lg text-white">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <span class="text-md font-bold text-white tracking-wider">SIP-DARURAT</span>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="flex flex-col flex-1 overflow-y-auto">
                    <nav class="flex-1 px-3 py-4 space-y-1.5">
                        @include('layouts.sidebar-links')
                    </nav>
                    
                    <!-- Footer Profile Sidebar -->
                    <div class="flex flex-shrink-0 p-4 bg-gray-950 border-t border-gray-800">
                        <div class="flex items-center">
                            <div>
                                @if(auth()->user()->photo)
                                    <img class="inline-block w-9 h-9 rounded-full" src="{{ asset('storage/' . auth()->user()->photo) }}" alt="Foto Profil">
                                @else
                                    <div class="flex items-center justify-center w-9 h-9 bg-red-600 rounded-full text-white font-bold text-sm">
                                        {{ substr(auth()->user()->name, 0, 2) }}
                                    </div>
                                @endif
                            </div>
                            <div class="ml-3">
                                <p class="text-xs font-semibold text-white truncate max-w-[150px]">{{ auth()->user()->name }}</p>
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-red-900 text-red-200 capitalize mt-0.5">
                                    {{ auth()->user()->role }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex flex-col flex-1 w-0 overflow-hidden">
            <!-- Topbar -->
            <div class="relative z-10 flex flex-shrink-0 h-16 bg-white border-b border-gray-200 shadow-sm">
                <!-- Toggle Sidebar Button Mobile -->
                <button type="button" class="px-4 text-gray-500 border-r border-gray-200 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-red-500 md:hidden" @click="sidebarOpen = true">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>

                <!-- Topbar Content -->
                <div class="flex justify-between flex-1 px-4 sm:px-6">
                    <!-- Title/Search Placeholder -->
                    <div class="flex items-center">
                        <h1 class="text-lg font-bold text-gray-800 md:text-xl tracking-tight">
                            @yield('page-title', 'Dashboard')
                        </h1>
                    </div>

                    <!-- User Dropdown & Action -->
                    <div class="flex items-center ml-4 md:ml-6">
                        <!-- Profile Dropdown -->
                        <div class="relative ml-3" x-data="{ open: false }">
                            <div>
                                <button type="button" class="flex items-center max-w-xs text-sm bg-white rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" @click="open = !open">
                                    <span class="sr-only">Open user menu</span>
                                    <div class="flex items-center space-x-2">
                                        @if(auth()->user()->photo)
                                            <img class="w-8 h-8 rounded-full" src="{{ asset('storage/' . auth()->user()->photo) }}" alt="Foto Profil">
                                        @else
                                            <div class="flex items-center justify-center w-8 h-8 bg-red-600 rounded-full text-white font-bold text-xs">
                                                {{ substr(auth()->user()->name, 0, 2) }}
                                            </div>
                                        @endif
                                        <span class="hidden md:inline-block text-sm font-medium text-gray-700">{{ auth()->user()->name }}</span>
                                        <svg class="hidden w-5 h-5 text-gray-400 md:block" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </div>

                            <!-- Dropdown Menu -->
                            <div class="absolute right-0 z-10 w-48 mt-2 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" style="display: none;">
                                <div class="py-1">
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Ubah Profil</a>
                                    
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100">Keluar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <main class="flex-1 relative z-0 overflow-y-auto focus:outline-none bg-slate-50 p-4 sm:p-6 lg:p-8">
                <!-- Notifications Alert -->
                @if (session('success'))
                    <div class="p-4 mb-6 bg-green-50 border-l-4 border-green-500 rounded-r-md" x-data="{ show: true }" x-show="show" x-transition>
                        <div class="flex justify-between items-center">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                            </div>
                            <button @click="show = false" class="text-green-500 hover:text-green-700 focus:outline-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="p-4 mb-6 bg-red-50 border-l-4 border-red-500 rounded-r-md" x-data="{ show: true }" x-show="show" x-transition>
                        <div class="flex justify-between items-center">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                            </div>
                            <button @click="show = false" class="text-red-500 hover:text-red-700 focus:outline-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
