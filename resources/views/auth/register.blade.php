<x-guest-layout>
    <div class="flex flex-col items-center justify-center min-h-screen bg-gradient-to-br from-pink-400 via-purple-400 to-indigo-400 p-4">
        <!-- Card -->
        <div class="w-full max-w-sm bg-white rounded-xl shadow-lg p-6 transform transition-all duration-500 hover:scale-105 hover:shadow-pink-200/50 animate__animated animate__fadeInDown text-sm">

            <!-- Logo -->
            <div class="flex justify-center mb-4">
                <a href="{{ route('dashboard') }}">
                    <img src="{{ asset('logo-ah.png') }}" alt="Logo" class="h-12 w-auto animate__animated animate__pulse animate__infinite" />
                </a>
            </div>

            <!-- Judul -->
            <h2 class="text-center text-lg font-bold text-gray-800 mb-1">Create Account 🚀</h2>
            <p class="text-center text-xs text-gray-500 mb-4">Fill in the form to get started</p>

            <form method="POST" action="{{ route('registeruser') }}">
                @csrf

                <!-- Name -->
                <div class="mb-3">
                    <x-input-label for="name" :value="__('Name')" class="text-xs" />
                    <x-text-input id="name"
                        class="block mt-1 w-full rounded-md border-gray-300 focus:border-pink-400 focus:ring-1 focus:ring-pink-300 text-sm"
                        type="text"
                        name="name"
                        :value="old('name')"
                        required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-1 text-xs text-red-500" />
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <x-input-label for="email" :value="__('Email')" class="text-xs" />
                    <x-text-input id="email"
                        class="block mt-1 w-full rounded-md border-gray-300 focus:border-pink-400 focus:ring-1 focus:ring-pink-300 text-sm"
                        type="email"
                        name="email"
                        :value="old('email')"
                        required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-red-500" />
                </div>

                <!-- Role -->
                <div class="mb-3">
                    <label for="role" class="block text-xs font-medium text-gray-700">Pilih Role</label>
                    <select name="role" id="role"
                        class="mt-1 block w-full rounded-md border-gray-300 focus:border-pink-400 focus:ring-1 focus:ring-pink-300 text-sm"
                        required>
                        <option value="">-- Pilih Role --</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('role')" class="mt-1 text-xs text-red-500" />
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <x-input-label for="password" :value="__('Password')" class="text-xs" />
                    <x-text-input id="password"
                        class="block mt-1 w-full rounded-md border-gray-300 focus:border-pink-400 focus:ring-1 focus:ring-pink-300 text-sm"
                        type="password"
                        name="password"
                        required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-red-500" />
                </div>

                <!-- Confirm Password -->
                <div class="mb-4">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-xs" />
                    <x-text-input id="password_confirmation"
                        class="block mt-1 w-full rounded-md border-gray-300 focus:border-pink-400 focus:ring-1 focus:ring-pink-300 text-sm"
                        type="password"
                        name="password_confirmation"
                        required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-xs text-red-500" />
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-between">
                    <a class="text-xs text-pink-500 hover:text-pink-700 transition" href="{{ route('login') }}">
                        {{ __('Already registered?') }}
                    </a>
                    <x-primary-button class="px-4 py-1.5 bg-pink-500 hover:bg-pink-600 rounded-md text-sm transition shadow-sm hover:shadow-md">
                        {{ __('Register') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tambahan animate.css -->
    <link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">
</x-guest-layout>
