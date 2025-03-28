@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Login Successful</h1>
        <p>Your API token has been generated. Redirecting to dashboard...</p>
        <script>
            localStorage.setItem('token', '{{ $token }}');
            setTimeout(() => window.location.href = '{{ route('dashboard') }}', 2000);
        </script>
    </div>
@endsection
