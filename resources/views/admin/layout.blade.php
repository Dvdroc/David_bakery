<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - David Bakery</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        /* Kelas khusus untuk warna aktif sesuai permintaan */
        .bg-sage-green { background-color: #A3B18A; }
    </style>
</head>
<body class="bg-gray-50">

    <div class="flex h-screen overflow-hidden">

        <aside class="w-64 bg-white border-r border-gray-200 flex flex-col justify-between hidden md:flex">
            
            <div>
                <div class="h-20 flex items-center px-8 border-b border-gray-100">
                    
                    <h1 class="text-2xl font-bold text-gray-800 tracking-wide">
                        David<span style="color: #A3B18A">Bakery</span>
                    </h1>

                    <svg class="w-40 h-40  text-[#A3B18A]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill="currentColor" d="M256 92.3c-53.2 0-102.8 14.8-142.2 37.4l14.2 22.4l-15.2 9.6l-14.23-22.5c-2.17 1.4-4.3 2.9-6.39 4.4c-38.75 27.9-63.12 63.7-66.7 97.2c2.98 6.7 8.12 17.5 14.74 28.8c10.73 18.3 26.22 36.6 37.19 39.8c17.92-2.5 33.99-22.1 50.29-19.3c25.1 5.1 38.1 28.4 50.3 48c9.3 14.5 21.1 34.7 38.1 36.5c6.2.7 11.6-1.2 17.7-5.2c6.1-4.1 12.5-10.4 19.2-17.3c13.2-13.7 27.7-30.9 49.5-33.6c15.3-2 27.6 6.2 38.1 12.4c10.4 6.1 18.7 10.2 25.5 8.6h.1c11.1-5.4 16.8-14.8 22.1-23.2l-35.6-25.4l10.4-14.6l35.9 25.6c5.1-5.5 11.4-10 19.7-11.8c11.5-2.5 20.4 3.8 27.1 8c6.6 4.2 11.2 6.3 13.8 5.8c3.1-.6 11.8-7 18.1-13.8c2-2.2 3.9-4.4 5.4-6.3q3.9-16.35 3.9-34.5c0-1.5-.1-3-.1-4.4l-37.6 18.7l-8-16.2l42.4-21.2c-8-29-30.7-58.7-63.9-82.6c-42-30.3-100.3-51.3-163.8-51.3m69.1 14.2l6 17l-50.3 18.1l-6-17zm-140.2 11l33.4 9.6l-5 17.2l-33.4-9.5zm184.4 23l39.7 27.6l-10.2 14.8l-39.7-27.6zm-47.6 12.8l10.8 14.4l-33 24.5l-10.8-14.4zm-157 34l2.6 17.8l-45.6 6.9l-2.6-17.8zm91.3 3.6c17.8 0 34 3.2 46.5 9.1c12.5 5.8 22.9 15 22.9 28s-10.4 22.2-22.9 28c-12.5 5.9-28.7 9.1-46.5 9.1s-34-3.2-46.5-9.1c-12.5-5.8-22.9-15-22.9-28s10.4-22.2 22.9-28c12.5-5.9 28.7-9.1 46.5-9.1m146.2 5.6l4.6 17.4l-47.2 12.2l-4.6-17.4zM256 208.9c-15.6 0-29.6 3-38.9 7.4c-9.4 4.3-12.5 9.2-12.5 11.7s3.1 7.4 12.5 11.7c9.3 4.4 23.3 7.4 38.9 7.4s29.6-3 38.9-7.4c9.4-4.3 12.5-9.2 12.5-11.7s-3.1-7.4-12.5-11.7c-9.3-4.4-23.3-7.4-38.9-7.4m-194.44 18l39.74 15.4l-6.5 16.8l-39.74-15.4zm-32 59.9c9.06 35.6 31.19 64.7 62.55 86.9c41.69 29.4 99.99 46 163.89 46s122.2-16.6 163.9-46c21.1-14.9 38.1-33 49.6-54.2c-2 .9-4.1 1.6-6.3 2c-11.5 2.4-20.4-4-27-8.2c-6.7-4.1-11.2-6.2-13.7-5.6c-7.2 1.6-13.4 9.7-20.6 20.8c-7.3 11-15.6 25-31.8 28.6c-15.2 3.4-28-4.4-38.7-10.7c-10.6-6.3-19.5-11-26.6-10.1c-16.8 5-29.2 18.3-38.9 28.3c-6.8 7.1-13.8 14.3-22.1 19.8s-18.3 9.2-29.5 8.2c-26.8-4.9-39.2-24.9-51.6-45c-12.3-19.7-23.5-36.8-38.6-39.9c-18.7 3-31.62 24.4-51.74 18.9c-18.43-5.4-32.01-22.5-42.8-39.8m223.64 2.8l2.6 17.8l-50.4 7.4l-2.6-17.8z"/>
                    </svg>
                </div>

                <nav class="mt-4 px-4 space-y-2">
                    
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center px-4 py-3 rounded-xl font-medium transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-sage-green text-white shadow-md' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        Dashboard
                    </a>

                    <a href="{{ route('admin.notifications') }}" 
                       class="flex items-center px-4 py-3 rounded-xl font-medium transition-colors {{ request()->routeIs('admin.notifications') ? 'bg-sage-green text-white shadow-md' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        Notifikasi
                    </a>

                    <a href="{{ route('admin.slots.index') }}" 
                        class="flex items-center px-4 py-3 rounded-xl font-medium transition-colors {{ request()->routeIs('admin.slots.*') ? 'bg-sage-green text-white shadow-md' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Daftar Slot
                        </a>

                        <a href="{{ route('admin.products.index') }}" 
                        class="flex items-center px-4 py-3 rounded-xl font-medium transition-colors {{ request()->routeIs('admin.products.*') ? 'bg-sage-green text-white shadow-md' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            Daftar Kue
                        </a>

                    <a href="{{ route('admin.orders.index') }}" 
                        class="flex items-center px-4 py-3 rounded-xl font-medium transition-colors {{ request()->routeIs('admin.orders.*') ? 'bg-sage-green text-white shadow-md' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                            Status Pesan
                        </a>

                    <a href="{{ route('admin.recap.index') }}" 
                        class="flex items-center px-4 py-3 rounded-xl font-medium transition-colors {{ request()->routeIs('admin.recap.*') ? 'bg-sage-green text-white shadow-md' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            Rekap
                        </a>

                </nav>
            </div>

            <div class="p-4 border-t border-gray-100">
                <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50 border border-gray-200">
                    
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gray-300 overflow-hidden shadow-sm">
                            <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name ?? 'Admin' }}&background=A3B18A&color=fff" alt="User">
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-800 truncate max-w-[90px]">
                                {{ Auth::user()->name ?? 'Admin Toko' }}
                            </p>
                            <p class="text-xs text-gray-500">Administrator</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Keluar / Logout">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                        </button>
                    </form>

                </div>
            </div>

        </aside>

        <main class="flex-1 h-full overflow-y-auto p-8">
            @yield('content')
        </main>

    </div>
</body>
</html>