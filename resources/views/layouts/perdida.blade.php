@extends('layouts.inicio')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center" style="margin-top: 10%">
            <div class="col-sm-4 col-sm-offset-4">
                <h2>Service Student Management</h2>
                @if ( Session::has('error') )
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            <span class="sr-only">Close</span>
                        </button>
                        <strong>{{ Session::get('error') }}</strong>
                    </div>
                @endif

                <form method="post" action="{{ route('login') }}">
                    {!! csrf_field() !!}

                    <div class="form-group">
                        <label for="dni">Introduce tu correo electrónico para recuperar la contraseña</label>
                        <input type="email" class="form-control" id="email" placeholder="Correo electrónico" name="email" required>
                    </div>

                    <div class="form-group">
                        <button class="btn btn-lg btn-primary btn-block" type="submit">Enviar</button>
                    </div>

                    <a href="<?php echo url('/')?>"><input type= "button"  value="Atrás" class="login-button"></a>


                </form>
            </div>
        </div>
    </div>
@endsection