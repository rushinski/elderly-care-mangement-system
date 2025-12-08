{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'OHMS')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- If you're using Vite / Breeze --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900">
<div class="min-h-screen flex">

    {{-- Sidebar --}}
    @include('components.sidebar')

    <div class="flex-1 flex flex-col min-h-screen">
        {{-- Navbar --}}
        @include('components.navbar')

        {{-- Flash messages --}}
        <main class="flex-1 px-4 py-6 md:px-8">
            @if(session('success'))
                <div class="mb-4 p-3 rounded bg-green-100 text-green-800 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 p-3 rounded bg-red-100 text-red-800 text-sm">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>
</body>
</html>
