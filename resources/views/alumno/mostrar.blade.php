@extends('layouts.app_OLD')

@section('title','Formulario')

@section('content')


    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{$error}}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(count($asignaturas)!= 0)
    <form class="form-group" method="POST" action="{{dirname($_SERVER['PHP_SELF']) . '/practicas'}}" enctype="multipart/form-data">
        @csrf
        <div class="card" style="margin-left: auto; margin-right: auto; width: 60rem; margin-top: 70px; padding: 4%;">


                    <div class="row">
                        <div class="col-sm">
                            <h5 class="card-title">Resultado de prácticas por asignatura</h5>

                            <div class="card text-center" style="margin-left: auto; margin-right: auto; width: 50rem; margin-top: 70px; margin-bottom: 30px;">
                                <select name="asignatura" size="<?php echo count($asignaturas)?>">
                                    @foreach($asignaturas as $asignatura)
                                    <option value="{{$asignatura->id}}">{{$asignatura->name}}</option>
                                    @endforeach
                                </select>
                                <input name="vista" value="resultado" hidden/>
                                <button type="submit" style="margin-top: 2%" class="btn btn-primary">Seleccionar</button>
                            </div>
                        </div>
                    </div>

        </div>
    </form>
           @else
        <div class="row">
            <div class="col-sm">
                <div class="card text-center" style="margin-left: auto; margin-right: auto; width: 50rem; margin-top: 70px; margin-bottom: 30px;">
                <h5 class="card-title">No existen asignaturas</h5>
                </div>
            </div>
        </div>
            @endif



@endsection