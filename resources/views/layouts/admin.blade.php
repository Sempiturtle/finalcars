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
        html {
            font-size: 14px; /* More compact global scale */
        }
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
        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e5e7eb;
            border-radius: 20px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #d1d5db;
        }
    </style>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}?v=2">
</head>
<body class="bg-gray-50 text-gray-900 antialiased overflow-x-hidden">
    <div class="flex h-screen overflow-hidden" 
         x-data="{ 
            sidebarOpen: false 
         }">
        
        <!-- Sidebar Overlay -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="sidebarOpen = false"
             class="fixed inset-0 bg-black/40 backdrop-blur-sm z-40">
        </div>

        <!-- Sidebar -->
        <aside 
            class="fixed inset-y-0 left-0 z-50 w-60 bg-white shadow-2xl transition-transform duration-500 cubic-bezier(0.4, 0, 0.2, 1) transform border-r border-gray-100"
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
                <nav class="flex-1 px-4 space-y-0.5 overflow-y-auto mt-4 custom-scrollbar">
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-item flex items-center px-4 py-1.5 rounded-xl text-sm font-bold {{ request()->routeIs('admin.dashboard') ? 'active' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="h-5 w-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        Dashboard
                    </a>
                    
                    <a href="{{ route('admin.vehicles.index') }}" class="sidebar-item flex items-center px-4 py-1.5 rounded-xl text-sm font-bold {{ request()->routeIs('admin.vehicles.*') ? 'active' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="h-5 w-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        Vehicle Fleet
                    </a>

                    <a href="{{ route('admin.service-types.index') }}" class="sidebar-item flex items-center px-4 py-1.5 rounded-xl text-sm font-bold {{ request()->routeIs('admin.service-types.*') ? 'active' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="h-5 w-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Service Types
                    </a>

                    <a href="{{ route('admin.maintenance.index') }}" class="sidebar-item flex items-center px-4 py-1.5 rounded-xl text-sm font-bold {{ request()->routeIs('admin.maintenance.index') ? 'active' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="h-5 w-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Maintenance Schedule
                    </a>

                    <a href="{{ route('admin.maintenance.timeline') }}" class="sidebar-item flex items-center px-4 py-1.5 rounded-xl text-sm font-bold {{ request()->routeIs('admin.maintenance.timeline') ? 'active' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="h-5 w-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Timeline Monitoring
                    </a>

                    <a href="{{ route('admin.notifications.index') }}" class="sidebar-item flex items-center px-4 py-1.5 rounded-xl text-sm font-bold {{ request()->routeIs('admin.notifications.*') ? 'active' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="h-5 w-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        Email Notifications
                    </a>

                    <a href="{{ route('admin.attention-required') }}" class="sidebar-item flex items-center px-4 py-1.5 rounded-xl text-sm font-bold {{ request()->routeIs('admin.attention-required') ? 'active' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="h-5 w-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        Overdue Alerts
                    </a>

                    <a href="{{ route('admin.service-history.index') }}" class="sidebar-item flex items-center px-4 py-1.5 rounded-xl text-sm font-bold {{ request()->routeIs('admin.service-history.*') ? 'active' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="h-5 w-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        Service History
                    </a>

                    <a href="{{ route('admin.users.index') }}" class="sidebar-item flex items-center px-4 py-1.5 rounded-xl text-sm font-bold {{ request()->routeIs('admin.users.*') ? 'active' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="h-5 w-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        User Management
                    </a>
                    <a href="{{ route('admin.points.index') }}" class="sidebar-item flex items-center px-4 py-1.5 rounded-xl text-sm font-bold {{ request()->routeIs('admin.points.*') ? 'active' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="h-5 w-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        Pointing System
                    </a>
                    <a href="{{ route('admin.rewards.index') }}" class="sidebar-item flex items-center px-4 py-1.5 rounded-xl text-sm font-bold {{ request()->routeIs('admin.rewards.*') ? 'active' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="h-5 w-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path></svg>
                        Loyalty Rewards
                    </a>
                    <a href="{{ route('admin.reports.index') }}" class="sidebar-item flex items-center px-4 py-1.5 rounded-xl text-sm font-bold {{ request()->routeIs('admin.reports.*') ? 'active' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="h-5 w-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        Analytics / Tracking
                    </a>
                    <a href="{{ route('admin.chat.index') }}" 
                       x-data="{ unreadCount: 0, poll() { fetch('{{ route('chat.unread-count') }}').then(r => r.json()).then(d => this.unreadCount = d.total) } }"
                       x-init="poll(); setInterval(() => poll(), 10000)"
                       class="sidebar-item flex items-center justify-between px-4 py-1.5 rounded-xl text-sm font-bold {{ request()->routeIs('admin.chat.*') ? 'active' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                            Chat Center
                        </div>
                        <template x-if="unreadCount > 0">
                            <span class="bg-red-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full animate-pulse" x-text="unreadCount"></span>
                        </template>
                    </a>
                </nav>

                <!-- User Footer -->
                <div class="p-4 border-t border-gray-100">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-4 py-1.5 rounded-xl text-sm font-bold text-red-600 hover:bg-red-50 transition-colors">
                            <svg class="h-5 w-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 h-screen overflow-y-auto">
            <!-- Topbar -->
            <header class="h-20 bg-white border-b border-gray-100 flex items-center justify-between px-8 sticky top-0 z-40">
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-xl text-gray-400 hover:bg-gray-50 hover:text-gray-600 focus:outline-none transition-colors">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                </button>

                <div class="flex items-center space-x-4">
                    <!-- Test Email Trigger -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="p-2.5 rounded-xl text-gray-400 hover:bg-gray-50 hover:text-autocheck-red focus:outline-none transition-all group" title="Send Test Email">
                            <svg class="h-6 w-6 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </button>
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                             @click.away="open = false" 
                             class="fixed inset-x-4 md:absolute md:inset-auto md:right-0 mt-3 w-auto md:w-72 bg-white rounded-[2rem] md:rounded-3xl shadow-2xl border border-gray-100 p-6 z-50 overflow-hidden"
                             style="display: none;"
                        >
                            <div class="absolute top-0 right-0 -mt-2 -mr-2 w-16 h-16 bg-autocheck-red/5 rounded-full"></div>
                            
                            <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4 relative z-10">Manual Test Email</h4>
                            <p class="text-[10px] text-gray-500 mb-4 font-medium leading-relaxed">Select a user to send a sample maintenance reminder.</p>
                            
                            <form action="{{ route('admin.test-email.send') }}" method="POST" class="relative z-10">
                                @csrf
                                <div class="mb-4">
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 font-bold select-none">Select Recipient</label>
                                    <select name="user_id" class="w-full text-xs font-bold bg-gray-50 border-gray-100 rounded-2xl focus:ring-autocheck-red/20 focus:border-autocheck-red focus:bg-white transition-all py-3 px-4">
                                        @foreach($allCustomers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->email }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="w-full py-4 bg-autocheck-red text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-2xl hover:bg-red-700 transition-all shadow-lg shadow-red-500/20 active:scale-[0.98]">
                                    Send Test Now
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="h-8 w-px bg-gray-100 mx-2"></div>

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
            <div class="p-5">
                @if(session('success'))
                    <div class="mb-8 p-6 bg-green-50 rounded-3xl border border-green-100 flex items-center space-x-4 animate-fade-in shadow-sm">
                        <div class="flex-shrink-0 w-12 h-12 bg-green-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-green-500/20">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <p class="text-green-800 font-bold tracking-tight">{{ session('success') }}</p>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-8 p-6 bg-red-50 rounded-3xl border border-red-100 flex items-center space-x-4 animate-fade-in shadow-sm">
                        <div class="flex-shrink-0 w-12 h-12 bg-autocheck-red rounded-2xl flex items-center justify-center text-white shadow-lg shadow-red-500/20">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <p class="text-red-800 font-bold tracking-tight">{{ session('error') }}</p>
                    </div>
                @endif

                {{ $slot }}
            </div>
        </main>
    </div>
</body>
</html>
