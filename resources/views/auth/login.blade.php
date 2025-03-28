@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4 max-w-md">
        <h1 class="text-2xl font-bold mb-4">Login</h1>
        @if ($errors->has('email'))
            <div class="text-red-500 mb-4">{{ $errors->first('email') }}</div>
        @endif
        <form id="loginForm" method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-4">
                <label for="email" class="block">Email</label>
                <input type="email" id="email" name="email" class="w-full p-2 border" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block">Password</label>
                <input type="password" id="password" name="password" class="w-full p-2 border" required>
            </div>
            <button type="submit" class="bg-blue-500 text-white p-2 rounded">Login</button>
        </form>
    </div>
@endsection
