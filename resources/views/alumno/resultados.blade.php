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




        <div class="card" style="margin-left: auto; margin-right: auto; width: 60rem; margin-top: 70px; padding: 4%;">
                    <div class="row">
                        <div class="col-sm">

                        @if($notafinal>=0)
                                <h5 class="card-title">Resultados de la práctica {{$pname}}</h5>
                                <h5 class="card-title">Número de archivos:{{count($archivos)}}</h5>
                            <h5 class="card-title">Nota final: {{number_format($notafinal,2)}}</h5>
                            @else
                                <h5 class="card-title">Resultados</h5>
                            @endif
                            @foreach($archivos as $archivo)
                            <div class="card text-center" style="margin-left: auto; margin-right: auto; width: 50rem; margin-top: 70px; margin-bottom: 30px;">
                                <h3 class="card-title">{{$archivo->name}}</h3>
                                <h5 class="card-title">TOTAL: {{$archivo->total}}</h5>
                                <h5 class="card-title">Pasados: {{$archivo->pasados}}</h5>
                                <h5 class="card-title">Fallados: {{$archivo->fallados}}</h5>
                                @if($archivo->fallados!=0)
                                    @foreach ($failresults as $fail)
                                    <h5 class="card-title">Descripción: {{$fail}}</h5>
                                    @endforeach
                                @endif
                                <h5 class="card-title">Intentos restantes: {{$archivo->intentos}}</h5>
                                <h5 class="card-title">Nota: {{number_format($archivo->nota,2)}}</h5>
                            </div>
                            @endforeach
                        </div>
                    </div>
        </div>


@endsection