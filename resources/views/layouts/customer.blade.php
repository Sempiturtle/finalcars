<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'AutoCheck') }} - Customer Portal</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        html { font-size: 14.5px; }
        body { font-family: 'Outfit', sans-serif; }
        .bg-autocheck-red { background-color: #F53003; }
        .text-autocheck-red { color: #F53003; }
        .sidebar-item { transition: all 0.2s ease; }
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
        
        <!-- Sidebar Overlay (Active on both Mobile and Desktop when sidebar is open) -->
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
            class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-2xl transition-transform duration-500 cubic-bezier(0.4, 0, 0.2, 1) transform border-r border-gray-100"
            :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }"
        >
            <div class="flex flex-col h-full">
                <!-- Branding -->
                <div class="h-20 flex items-center px-8 border-b border-gray-100 shrink-0">
                    <div class="flex items-center space-x-3">
                        <img src="{{ asset('images/logo.png') }}" alt="AutoCheck Logo" class="h-10 w-10 rounded-full object-cover border-2 border-autocheck-red">
                        <span class="text-xl font-black tracking-tight">AutoCheck <span class="text-autocheck-red">User</span></span>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-4 space-y-1 overflow-y-auto mt-6 custom-scrollbar">
                    <a href="{{ route('customer.landing') }}" class="sidebar-item flex items-center px-4 py-2.5 rounded-xl text-sm font-bold {{ request()->routeIs('customer.landing') || request()->routeIs('customer.dashboard') ? 'active' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="h-5 w-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        Home
                    </a>

                    <a href="{{ route('customer.vehicles.index') }}" class="sidebar-item flex items-center px-4 py-2.5 rounded-xl text-sm font-bold {{ request()->routeIs('customer.vehicles.*') ? 'active' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="h-5 w-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        Vehicle Fleet
                    </a>

                    <a href="{{ route('customer.maintenance.timeline') }}" class="sidebar-item flex items-center px-4 py-2.5 rounded-xl text-sm font-bold {{ request()->routeIs('customer.maintenance.timeline') ? 'active' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="h-5 w-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Maintenance Timeline
                    </a>

                    <a href="{{ route('customer.chat.index') }}" 
                       x-data="{ unreadCount: 0, poll() { fetch('{{ route('chat.unread-count') }}').then(r => r.json()).then(d => this.unreadCount = d.total) } }"
                       x-init="poll(); setInterval(() => poll(), 10000)"
                       class="sidebar-item flex items-center justify-between px-4 py-2.5 rounded-xl text-sm font-bold {{ request()->routeIs('customer.chat.*') ? 'active' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                            Messaging Center
                        </div>
                        <template x-if="unreadCount > 0">
                            <span class="bg-red-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full animate-pulse" x-text="unreadCount"></span>
                        </template>
                    </a>

                    <a href="{{ route('customer.rewards.index') }}" class="sidebar-item flex items-center px-4 py-2.5 rounded-xl text-sm font-bold {{ request()->routeIs('customer.rewards.*') ? 'active' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="h-5 w-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path></svg>
                        Loyalty Rewards
                    </a>

                    <a href="{{ route('customer.history.index') }}" class="sidebar-item flex items-center px-4 py-2.5 rounded-xl text-sm font-bold {{ request()->routeIs('customer.history.*') ? 'active' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="h-5 w-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        Service History
                    </a>

                    <a href="{{ route('customer.profile.index') }}" class="sidebar-item flex items-center px-4 py-2.5 rounded-xl text-sm font-bold {{ request()->routeIs('customer.profile.*') ? 'active' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="h-5 w-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Account Settings
                    </a>
                </nav>

                <!-- User Footer -->
                <div class="p-4 border-t border-gray-100">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-4 py-2.5 rounded-xl text-sm font-bold text-red-600 hover:bg-red-50 transition-colors">
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
                <div class="flex items-center space-x-6">
                    <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-xl text-gray-400 hover:bg-gray-50 hover:text-gray-600 focus:outline-none transition-colors">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                    </button>
                    <h2 class="text-sm font-black text-gray-400 uppercase tracking-[0.3em] hidden md:block italic">Dashboard <span class="text-autocheck-red">/</span> {{ request()->route()->getName() }}</h2>
                </div>

                <div class="flex items-center space-x-6" x-data="{ 
                    notifOpen: false, 
                    notifications: [], 
                    unreadCount: 0,
                    fetchNotifications() {
                        fetch('{{ route('customer.notifications.index') }}')
                            .then(res => res.json())
                            .then(data => {
                                this.notifications = data.notifications;
                                this.unreadCount = data.unreadCount;
                            });
                    },
                    markAsRead(id) {
                        fetch(`/customer/notifications/${id}/read`, {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                        }).then(() => this.fetchNotifications());
                    },
                    markAllRead() {
                        fetch('{{ route('customer.notifications.read-all') }}', {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                        }).then(() => this.fetchNotifications());
                    }
                }" x-init="fetchNotifications(); setInterval(() => fetchNotifications(), 30000)">
                    
                    <!-- Notification Bell Dropdown -->
                    <div class="relative">
                        <button @click="notifOpen = !notifOpen" class="relative p-2.5 rounded-xl text-gray-400 hover:bg-gray-50 hover:text-autocheck-red transition-all duration-300 focus:outline-none group">
                            <svg class="h-6 w-6 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            <template x-if="unreadCount > 0">
                                <span class="absolute top-1.5 right-1.5 h-4 w-4 bg-autocheck-red text-white text-[8px] font-black flex items-center justify-center rounded-full border-2 border-white shadow-lg shadow-red-500/40 animate-bounce" x-text="unreadCount"></span>
                            </template>
                        </button>

                        <div x-show="notifOpen" @click.away="notifOpen = false" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             class="fixed inset-x-4 md:absolute md:inset-auto md:right-0 mt-4 w-auto md:w-96 bg-white/95 backdrop-blur-xl rounded-[2rem] md:rounded-[2.5rem] shadow-2xl border border-gray-100 overflow-hidden z-50 origin-top-right">
                            
                            <div class="p-6 border-b border-gray-50 flex items-center justify-between bg-gray-50/50">
                                <h3 class="text-sm font-black text-gray-900 tracking-tight italic">Notifications <span class="text-autocheck-red" x-text="notifications.length"></span></h3>
                                <button @click="markAllRead()" class="text-[9px] font-black text-autocheck-red uppercase tracking-widest hover:underline italic">Clear All</button>
                            </div>

                            <div class="max-h-[400px] overflow-y-auto custom-scrollbar">
                                <template x-if="notifications.length === 0">
                                    <div class="p-12 text-center">
                                        <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center text-gray-200 mx-auto mb-4">
                                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                        </div>
                                        <p class="text-xs font-bold text-gray-400 italic">No activity detected.</p>
                                    </div>
                                </template>

                                <div class="divide-y divide-gray-50">
                                    <template x-for="notif in notifications" :key="notif.id">
                                        <div class="p-5 hover:bg-gray-50 transition-colors cursor-pointer group flex items-start space-x-4" 
                                             :class="!notif.read_at ? 'bg-red-50/30 border-r-4 border-autocheck-red' : ''"
                                             @click="markAsRead(notif.id); if(notif.data.url) window.location = notif.data.url">
                                            <div class="mt-1 w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center shrink-0 group-hover:bg-white transition-colors">
                                                <svg class="h-5 w-5 text-gray-400 group-hover:text-autocheck-red" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-html="notif.data.icon || '<path stroke-linecap round stroke-linejoin round stroke-width 2 d=\'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z\' />'"></svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-xs font-black text-gray-900 tracking-tight leading-tight mb-1" x-text="notif.data.title"></p>
                                                <p class="text-[10px] font-bold text-gray-500 truncate italic" x-text="notif.data.message"></p>
                                                <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest mt-2 block" x-text="new Date(notif.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})"></p>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                            
                            <div class="p-4 bg-gray-900 text-center">
                                <a href="#" class="text-[9px] font-black text-white uppercase tracking-[0.3em] hover:text-autocheck-red transition-colors italic">View Audit Log</a>
                            </div>
                        </div>
                    </div>

                    <div class="h-8 w-px bg-gray-100 hidden md:block"></div>

                    <div class="flex items-center space-x-6">
                        <div class="flex flex-col items-end hidden md:flex">
                            <span class="text-sm font-bold text-gray-900 leading-none">{{ Auth::user()->name }}</span>
                            <span class="text-[8px] font-black text-autocheck-red uppercase tracking-widest italic mt-1">Registered Legacy</span>
                        </div>
                        <div class="h-10 w-10 bg-gradient-to-br from-autocheck-red to-red-800 rounded-xl flex items-center justify-center text-white font-black shadow-lg shadow-red-500/20 border-2 border-white transform hover:rotate-6 transition-transform">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="p-6">
                {{ $slot }}
            </div>
        </main>
    </div>
</body>
</html>
