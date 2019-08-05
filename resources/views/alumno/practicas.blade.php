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

    @if(count($practicas)!= 0)
    <form class="form-group" method="POST" action="{{dirname($_SERVER['PHP_SELF']) . '/archivos'}}" enctype="multipart/form-data">
        @csrf
        <div class="card" style="margin-left: auto; margin-right: auto; width: 60rem; margin-top: 70px; padding: 4%;">


                    <div class="row">
                        <div class="col-sm">
                            <h5 class="card-title">Prácticas de la asignatura {{$practicas[0]->asname}}</h5>
                            <div class="card text-center" style="margin-left: auto; margin-right: auto; width: 50rem; margin-top: 70px; margin-bottom: 30px;">
                                <select name="practica" size="<?php echo count($practicas)?>">
                                    @foreach($practicas as $practica)
                                    <option value="{{$practica->id}}">{{$practica->name }} - {{ $practica->convocatoria}}</option>
                                    @endforeach
                                </select>
                                <input name="vista" value="{{$practicas[0]->vista}}" hidden/>
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
                <h5 class="card-title">No existen prácticas</h5>
                </div>
            </div>
        </div>
            @endif



@endsection