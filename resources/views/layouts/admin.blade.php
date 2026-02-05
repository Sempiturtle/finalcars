<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'AutoCheck') }} - Admin Dashboard</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
        .bg-autocheck-red {
            background-color: #F53003;
        }
        .text-autocheck-red {
            color: #F53003;
        }
        .sidebar-item {
            transition: all 0.2s ease;
        }
        .sidebar-item.active {
            background-color: #F53003;
            color: white;
            box-shadow: 0 10px 15px -3px rgba(245, 48, 3, 0.3);
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased overflow-x-hidden">
    <div class="flex h-screen overflow-hidden" x-data="{ sidebarOpen: true }">
        <!-- Sidebar -->
        <aside 
            class="fixed inset-y-0 left-0 z-50 w-72 bg-white shadow-2xl transition-transform duration-300 transform border-r border-gray-100"
            :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }"
        >
            <div class="flex flex-col h-full">
                <!-- Branding -->
                <div class="h-20 flex items-center px-8 border-b border-gray-100 shrink-0">
                    <div class="flex items-center space-x-3">
                        <img src="{{ asset('images/logo.png') }}" alt="AutoCheck Logo" class="h-10 w-10 rounded-full object-cover border-2 border-autocheck-red">
                        <span class="text-xl font-black tracking-tight">AutoCheck <span class="text-autocheck-red">Admin</span></span>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-4 py-8 space-y-2 overflow-y-auto">
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-item flex items-center px-4 py-3.5 rounded-2xl text-sm font-bold {{ request()->routeIs('admin.dashboard') ? 'active' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="h-5 w-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        Dashboard
                    </a>
                    
                    <a href="{{ route('admin.vehicles.index') }}" class="sidebar-item flex items-center px-4 py-3.5 rounded-2xl text-sm font-bold {{ request()->routeIs('admin.vehicles.*') ? 'active' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="h-5 w-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        Vehicle Fleet
                    </a>

                    <a href="{{ route('admin.maintenance.index') }}" class="sidebar-item flex items-center px-4 py-3.5 rounded-2xl text-sm font-bold {{ request()->routeIs('admin.maintenance.*') ? 'active' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="h-5 w-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Maintenance Schedule
                    </a>

                    <a href="{{ route('admin.notifications.index') }}" class="sidebar-item flex items-center px-4 py-3.5 rounded-2xl text-sm font-bold {{ request()->routeIs('admin.notifications.*') ? 'active' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="h-5 w-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        Email Notifications
                    </a>

                    <a href="{{ route('admin.service-history.index') }}" class="sidebar-item flex items-center px-4 py-3.5 rounded-2xl text-sm font-bold {{ request()->routeIs('admin.service-history.*') ? 'active' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="h-5 w-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        Service History
                    </a>

                    <a href="{{ route('admin.users.index') }}" class="sidebar-item flex items-center px-4 py-3.5 rounded-2xl text-sm font-bold {{ request()->routeIs('admin.users.*') ? 'active' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="h-5 w-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        User Management
                    </a>
                    <a href="{{ route('admin.reports.index') }}" class="sidebar-item flex items-center px-4 py-3.5 rounded-2xl text-sm font-bold {{ request()->routeIs('admin.reports.*') ? 'active' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="h-5 w-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        Reports & Analytics
                    </a>
                </nav>

                <!-- User Footer -->
                <div class="p-4 border-t border-gray-100">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-4 py-3.5 rounded-2xl text-sm font-bold text-red-600 hover:bg-red-50 transition-colors">
                            <svg class="h-5 w-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main 
            class="flex-1 h-screen overflow-y-auto transition-all duration-300 transform" 
            :class="{ 'ml-72': sidebarOpen, 'ml-0': !sidebarOpen }"
        >
            <!-- Topbar -->
            <header class="h-20 bg-white border-b border-gray-100 flex items-center justify-between px-8 sticky top-0 z-40">
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-xl text-gray-400 hover:bg-gray-50 hover:text-gray-600 focus:outline-none transition-colors">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                </button>

                <div class="flex items-center space-x-6">
                    <div class="flex flex-col items-end">
                        <span class="text-sm font-bold text-gray-900">{{ Auth::user()->name }}</span>
                        <span class="text-xs font-bold text-autocheck-red uppercase tracking-widest">{{ Auth::user()->role }}</span>
                    </div>
                    <div class="h-10 w-10 bg-autocheck-red rounded-xl flex items-center justify-center text-white font-bold shadow-lg shadow-red-500/20">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="p-8">
                {{ $slot }}
            </div>
        </main>
    </div>
</body>
</html>
