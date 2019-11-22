@extends('layouts.inicio')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center" style="margin-top: 10%">
            <div class="col-sm-4 col-sm-offset-4">
                <h2>Service Student Management</h2>

                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        Has introducido mal el Email y/o la contraseña .<br><br>
                    </div>
                @endif
                @if ( Session::has('success') )
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            <span class="sr-only">Close</span>
                        </button>
                        <strong>{{ Session::get('success') }}</strong>
                    </div>
                @endif
                @if ( Session::has('error') )
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            <span class="sr-only">Close</span>
                        </button>
                        <strong>{{ Session::get('error') }}</strong>
                    </div>
                @endif

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
                    <div class="row">
                        <div class="form-group col-md-4">
                            <a class="btn btn-lg btn-primary btn-block" href="/login">{{ __('Volver') }}</a>
                        </div>
                        <div class="form-group col-md-8">
                            <button class="btn btn-lg btn-primary btn-block" type="submit">{{ __('auth.send_password') }}</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
