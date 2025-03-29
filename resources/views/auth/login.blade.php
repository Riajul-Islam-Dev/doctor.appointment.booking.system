@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h1 class="card-title text-center text-primary mb-4 fw-bold fs-2">Login</h1>

                        @if ($errors->has('email'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ $errors->first('email') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form id="loginForm" method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" id="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" id="password" name="password" class="form-control" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-block">Login</button>
                            </div>
                            <div class="mt-3">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <strong>Login as Doctor</strong>
                                        <div>
                                            <span>Email:</span> <code class="text-primary">alice@doctor.com</code><br>
                                            <span>Password:</span> <code class="text-primary">password</code>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Login as Patient</strong>
                                        <div>
                                            <span>Email:</span> <code class="text-primary">charlie@patient.com</code><br>
                                            <span>Password:</span> <code class="text-primary">password</code>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
