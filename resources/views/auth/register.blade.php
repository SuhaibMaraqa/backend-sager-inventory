@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Register') }} as a Pilot</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            {{-- <div class="form-group row justify-content-md-center">
                                <label for="name"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                                <div class="col-md-6">
                                    <input id="name" type="text"
                                        class="form-control @error('name') is-invalid @enderror" name="name"
                                        value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Name">

                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div> --}}

                            <div class="form-group row justify-content-md-center">
                                {{-- <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('First Name') }}</label> --}}

                                <div class="col-md-3">
                                    <input id="name" type="text"
                                        class="form-control @error('first-name') is-invalid @enderror" name="first-name"
                                        value="{{ old('first-name') }}" required autocomplete="first-name" autofocus
                                        placeholder="First Name">

                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                {{-- <label for="firstName"
                                    class="col-md-2 col-form-label text-md-right">{{ __('Last Name') }}</label> --}}

                                <div class="col-md-3">
                                    <input id="name" type="text"
                                        class="form-control @error('last-name') is-invalid @enderror" name="last-name"
                                        value="{{ old('last-name') }}" required autocomplete="last-name" autofocus
                                        placeholder="Last Name">

                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row justify-content-md-center">
                                {{-- <label for="email"
                                    class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label> --}}


                                <div class="col-md-6">
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" required autocomplete="email" placeholder="Email">

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row justify-content-md-center">
                                {{-- <label for="password"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label> --}}


                                <div class="col-md-6">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required autocomplete="new-password" placeholder="Password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row justify-content-md-center">
                                {{-- <label for="password-confirm"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label> --}}


                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control"
                                        name="password_confirmation" required autocomplete="new-password"
                                        placeholder="Confirm Password">
                                </div>
                            </div>

                            <div class="form-group row justify-content-md-center">
                                {{-- <label for="national-id"
                                    class="col-md-4 col-form-label text-md-right">{{ __('National ID') }}</label> --}}


                                <div class="col-md-6">
                                    <input id="national-id" type="text" class="form-control" name="national-id" required
                                        placeholder="National ID">
                                </div>
                            </div>

                            {{-- <div class="form-group row">
                                <label for="role" class="col-md-4 col-form-label text-md-right">Role</label>
                            </div> --}}

                            <div class="form-group row justify-content-md-center">
                                <div class="col-md-3 form-check form-check-inline">
                                    <input class="form-check-input ml-5" type="radio" name="role" id="pilot"
                                        value="pilot">
                                    <label class="form-check-label" for="pilot">pilot</label>
                                </div>

                                <div class="col-md-3 form-check form-check-inline">
                                    <input class="form-check-input ml-5" type="radio" name="role" id="center"
                                        value="center">
                                    <label class="form-check-label" for="center">center</label>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Register') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
