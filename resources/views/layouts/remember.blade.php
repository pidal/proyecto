@extends('layouts.inicio')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center" style="margin-top: 10%">
            <div class="col-sm-4 col-sm-offset-4">
                <h2>Service Student Management</h2>

                @if ( Session::has('error') )
                    <div class="alert alert-danger">
                        <strong>{{ Session::get('error') }}</strong>
                        <br><br>
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

                <form method="post" action="{{ route('remember') }}">
                    {!! csrf_field() !!}

                    <input type="hidden" name="user" value = "<?php echo $idUser; ?>" />
                    <input type="hidden" name="path" value = "<?php echo $path; ?>" />

                    <div class="form-group">
                        <label for="password">Nueva Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" maxlength="15" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Confirmar Contraseña</label>
                        <input type="password" class="form-control" id="confirmpassword" maxlength="15" name="confirmpassword" required>
                    </div>

                    <div class="form-group">
                        <button class="btn btn-lg btn-primary btn-block" type="submit">Guardar</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection





