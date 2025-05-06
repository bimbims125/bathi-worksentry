@extends('pages.auth.layouts.master')

@push('page-script')
    <!-- particles js -->
    {{-- <script src="{{ asset('') }}assets/libs/particles.js/particles.js"></script> --}}
    <!-- particles app js -->
    {{-- <script src="{{ asset('') }}assets/js/pages/particles.app.js"></script> --}}
    <!-- password-addon init -->
    <script src="{{ asset('') }}assets/js/pages/password-addon.init.js"></script>
@endpush

@section('content')
    <!-- auth page content -->
    <div class="auth-page-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center mt-sm-5 mb-4 text-white-50">
                        <div>
                            <a href="/auth/login" class="d-inline-block auth-logo">
                                {{-- <img src="{{ asset('') }}assets/images/bathi_logo.png" alt="" width="100px"> --}}
                                <h2 class="text-white">Bathi Worksentry</h2>
                            </a>
                        </div>
                        {{-- <p class="mt-3 fs-15 fw-medium">Premium Admin & Dashboard Template</p> --}}
                    </div>
                </div>
            </div>
            <!-- end row -->

            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card mt-4">

                        <div class="card-body p-4">
                            <div class="text-center mt-2">
                                <h5 class="text-primary">Welcome!</h5>
                                {{-- <p class="text-muted">Sign in to continue to Velzon.</p> --}}
                            </div>
                            <div class="p-2 mt-4">
                                @if (session()->has('error'))
                                    <div class="alert alert-danger alert-dismissible alert-label-icon label-arrow show"
                                        role="alert">
                                        <i class="ri-error-warning-line label-icon"></i><strong>Error</strong> -
                                        {{ session('error') }}
                                        <button type="button" class="btn-close" data-bs-dismiss=" alert"
                                            aria-label="Close"></button>
                                    </div>
                                @endif
                                <form action="{{ route('auth.authenticate')}}" method="POST" autocomplete="on">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" name="username"
                                            class="form-control @error('username') is-invalid @enderror" id="username"
                                            placeholder="Enter username" value="{{ old('username') }}">
                                        @error('username')
                                            <small class="text-danger">
                                                {{ $message }}
                                            </small>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        {{-- <div class="float-end">
                                            <a href="{{ route('password.forgot') }}" class="text-muted">Forgot
                                                password?</a>
                                        </div> --}}
                                        <label class="form-label" for="password-input">Password</label>
                                        <div class="position-relative auth-pass-inputgroup mb-3">
                                            <input type="password" name="password"
                                                class="form-control pe-5 password-input @error('password') border-danger @enderror"
                                                placeholder="Enter password" id="password-input">
                                            <button
                                                class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon"
                                                type="button" id="password-addon"><i
                                                    class="ri-eye-fill align-middle"></i></button>
                                            @error('password')
                                                <small class="text-danger">
                                                    {{ $message }}
                                                </small>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <button class="btn btn-success w-100" type="submit">Sign In</button>
                                    </div>

                                </form>
                            </div>
                        </div>
                        <!-- end card body -->
                    </div>
                    <!-- end card -->

                    {{-- <div class="mt-4 text-center">
                        <p class="mb-0">Don't have an account ? <a href="/auth-signup-basic"
                                class="fw-semibold text-primary text-decoration-underline"> Signup </a> </p>
                    </div> --}}

                </div>
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end auth page content -->
@endsection
