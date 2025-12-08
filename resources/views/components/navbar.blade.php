{{-- resources/views/components/navbar.blade.php --}}
<header class="w-full bg-white shadow-sm border-b">
    <div class="flex items-center justify-between px-4 py-3 md:px-8">
        <div>
            <h1 class="text-lg font-semibold">Old Home Management System</h1>
            @auth
                <p class="text-xs text-gray-500">
                    Logged in as: {{ Auth::user()->name }}
                    @if(Auth::user()->role)
                        ({{ Auth::user()->role->name }})
                    @endif
                </p>
            @endauth
        </div>

        <div class="flex items-center gap-4">
            @auth
                <form action="{{ route('logout') }}" method="GET">
                    <button
                        type="submit"
                        class="text-sm px-3 py-1 rounded bg-red-500 text-white hover:bg-red-600"
                    >
                        Logout
                    </button>
                </form>
            @endauth
        </div>
    </div>
</header>
