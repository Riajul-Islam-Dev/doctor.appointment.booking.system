@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h1 class="card-title text-success mb-4 fw-bold fs-2">Login Successful</h1>
                        <div class="alert alert-success" role="alert">
                            <p class="mb-0">Your API token has been generated. Redirecting to dashboard in <span
                                    id="countdown">2</span> seconds...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        localStorage.setItem('token', '{{ $token }}');

        let countdown = 2;
        const countdownElement = document.getElementById('countdown');
        countdownElement.textContent = countdown;

        const timer = setInterval(() => {
            countdown--;
            countdownElement.textContent = countdown;
            if (countdown <= 0) {
                clearInterval(timer);
                window.location.href = '{{ route('dashboard') }}';
            }
        }, 3000);
    </script>
@endsection
