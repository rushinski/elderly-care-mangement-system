{{-- resources/views/components/navbar.blade.php --}}
<header class="w-full bg-white shadow-sm">
    <div class="flex items-center justify-between px-4 py-3 md:px-8">
        <div class="flex items-center gap-3">
            <button class="md:hidden" @click="sidebarOpen = !sidebarOpen">
                <span class="sr-only">Toggle navigation</span>
                ☰
            </button>
            <div>
                <h1 class="text-lg font-semibold">Old Home Management System</h1>
                <p class="text-xs text-gray-500">
                    @auth
                        Role: {{ Auth::user()->role->name ?? 'N/A' }}
                    @endauth
                </p>
            </div>
        </div>

        <div class="flex items-center gap-4">
            @auth
                <span class="text-sm text-gray-700">
                    {{ Auth::user()->name }}
                </span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
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
