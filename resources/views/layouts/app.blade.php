{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'OHMS')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Vite (Laravel 10 default) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Alpine.js (lightweight interactivity) --}}
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 text-gray-900">
<div class="min-h-screen flex" x-data="{ sidebarOpen: false }">

    {{-- Sidebar --}}
    @include('components.sidebar')

    <div class="flex-1 flex flex-col min-h-screen">
        {{-- Top Navbar --}}
        @include('components.navbar')

        {{-- Main content --}}
        <main class="flex-1 px-4 py-6 md:px-8">
            @if(session('status'))
                <div class="mb-4 p-3 rounded bg-green-100 text-green-800 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>
</body>
</html>
