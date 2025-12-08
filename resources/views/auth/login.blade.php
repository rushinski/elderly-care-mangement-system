{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="max-w-md mx-auto">
    <x-card title="Login">
        <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold mb-1">Email</label>
                <input type="email" name="email"
                       value="{{ old('email') }}"
                       class="border rounded w-full px-2 py-1 text-sm">
            </div>

            <div>
                <label class="block text-xs font-semibold mb-1">Password</label>
                <input type="password" name="password"
                       class="border rounded w-full px-2 py-1 text-sm">
            </div>

            <div class="flex items-center justify-between">
                <button type="submit"
                        class="px-4 py-2 rounded bg-blue-600 text-white text-sm hover:bg-blue-700">
                    Login
                </button>

                <a href="{{ route('register') }}" class="text-xs text-blue-600 hover:underline">
                    Create an account
                </a>
            </div>
        </form>
    </x-card>
</div>
@endsection
