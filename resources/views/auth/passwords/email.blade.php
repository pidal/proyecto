@extends('layouts.inicio')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center" style="margin-top: 10%">
            <div class="col-sm-4 col-sm-offset-4">
                <h2>Service Student Management</h2>

                <form method="POST" action="{{ route('newpassword') }}">
                    @csrf

                    <div class="form-group">
                        <label for="email">{{ __('auth.email') }}</label>
                        <input type="email" class="form-control" id="email" placeholder="Email" name="email" value="{{ old('email') }}" required>
                    </div>
                    @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                    @endif
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
