@extends('layouts.auth')

@section('content')
    <div class="login-container">
        <div class="login-header">
            <h1>SCHULMANAGER</h1>
        </div>

        @if($errors->any())
            <div class="error-message bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="login-form">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group mb-4">
                    <label for="name" class="block text-gray-700 font-medium mb-2">Benutzername</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        class="form-input w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        autofocus
                    >
                </div>

                <div class="form-group mb-6">
                    <label for="password" class="block text-gray-700 font-medium mb-2">Passwort</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        class="form-input w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>

                <button
                    type="submit"
                    class="login-button w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150"
                >
                    Anmelden
                </button>
            </form>
        </div>

        <div class="login-footer mt-8 text-center text-gray-500 text-sm">
            <p>powered by PS-Solutions4You UG</p>
        </div>
    </div>
@endsection