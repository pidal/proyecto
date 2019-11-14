@extends('layouts.inicio')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center" style="margin-top: 10%">
            <div class="col-sm-4 col-sm-offset-4">
                <h2>Service Student Management</h2>

                <form method="POST" action="{{ route('newpassword') }}">
                    @csrf

                    <div class="form-group row">
                        <label for="email" class="col-md-2 col-form-label text-md-right">{{ __('auth.email') }}</label>

                        <div class="col-md-10">
                            <input id="email" type="email"
                                   class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
                                   value="{{ old('email') }}" required>

                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-2">
                            <button type="submit" class="btn btn-primary">
                                {{ __('auth.send_password') }}
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
