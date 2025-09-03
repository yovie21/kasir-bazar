<x-guest-layout>
    <div class="flex flex-col items-center justify-center min-h-screen bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 p-6">
        <!-- Card -->
        <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl p-8 transform transition-all duration-500 hover:scale-105 hover:shadow-indigo-300/50 animate__animated animate__fadeInDown">
            
            <!-- Logo -->
            <div class="flex justify-center mb-6">
                <a href="{{ route('dashboard') }}">
                    <img src="{{ asset('logo-ah.png') }}" alt="Logo" class="h-16 w-auto animate__animated animate__pulse animate__infinite" />
                </a>
            </div>

            <!-- Judul -->
            <h2 class="text-center text-2xl font-bold text-gray-800 mb-4">Welcome Back 👋</h2>
            <p class="text-center text-sm text-gray-500 mb-6">Login to your account</p>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="mb-4">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email"
                        class="block mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-400 transition"
                        type="email" 
                        name="email" 
                        :value="old('email')" 
                        required 
                        autofocus 
                        autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-500" />
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password"
                        class="block mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-400 transition"
                        type="password"
                        name="password"
                        required 
                        autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-500" />
                </div>

                <!-- Remember Me -->
                <div class="flex items-center mb-4">
                    <input id="remember_me" type="checkbox"
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                        name="remember">
                    <label for="remember_me" class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</label>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-between">
                    @if (Route::has('password.request'))
                        <a class="text-sm text-indigo-500 hover:text-indigo-700 transition" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif

                    <x-primary-button class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 rounded-lg transition shadow-md hover:shadow-lg">
                        {{ __('Log in') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tambahan animate.css -->
    <link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">
</x-guest-layout>
