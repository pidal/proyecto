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

    @if(count($archivos)!= 0)
      @foreach($archivos as $archivo)
          <form class="form-group" method="POST" action="{{dirname($_SERVER['PHP_SELF']) . '/guardar'}}" enctype="multipart/form-data">
              @csrf
              <div class="card" style="margin-left: auto; margin-right: auto; width: 60rem; margin-top: 70px; padding: 4%;">

              <div class="row">
                        <div class="col-sm">
                            <div class="card text-center" style="margin-left: auto; margin-right: auto; width: 50rem; margin-top: 70px; margin-bottom: 30px;">
                                <img style="height:30px; width: 30px; margin: 20px;" class="card-img-top rounded-circle mx-auto d-block" src="test/{{$archivo->extension}}">
                                <h5 class="card-title">Nombre: {{$archivo->name}}</h5>
                                <input type="hidden" name="name" value="{{$archivo->name}}">
                                <h5 class="card-title">Fecha límite: {{$archivo->expired_date}}</h5>
                                <input type="hidden" name="expired_date" value="{{$archivo->expired_date}}">
                                <h5 class="card-title">Peso: {{$archivo->weight}}</h5>
                                <input type="hidden" name="weight" value="{{$archivo->weight}}">
                                <h5 class="card-title">Intentos restantes: {{$archivo->intentos}}</h5>
                                <input type="hidden" name="intentos" value="{{$archivo->intentos}}">
                                <input type="hidden" name="extension" value="{{$archivo->extension}}">
                                <input type="hidden" name="id" value="{{$archivo->id}}">



                                <?php   $idRol = DB::table('profesor')->where('users_id',auth()->id())->value('roles_id');
                                if($idRol == null){
                                    $idRol = DB::table('alumno')->where('users_id',auth()->id())->value('roles_id');
                                };?>

                                @if($idRol == 2)
                                    <div class="form-group">
                                        <h5 class="card-title">Código fuente: {{$archivo->file_prof}}</h5>
                                    </div>
                                @else
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 col-form-label">Código fuente:</label>
                                        <input type="file"  name="file" >
                                    </div>
                                    <button type="submit" class="btn btn-primary">Enviar</button>
                                @endif
                            </div>
                        </div>
                    </div>
              </div>
          </form>
                @endforeach

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