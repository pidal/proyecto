@extends('layouts.app_OLD')

@section('title','Formulario')


<script>
    a = 0;
    function addArchivo(){
        a++;

        var div = document.createElement('div');
        div.setAttribute('class', 'form-inline');
        div.innerHTML = '<div class="form-group row"><div style="clear:both margin-left: auto; margin-right: auto; width: 60rem; " class="col-sm-10"><label>Nombre</lavel><input class="form-control" name="nombre_'+a+'" type="text"/></div></div><div class="form-group row"><div style="clear:both margin-left: auto; margin-right: auto; width: 60rem;" class="col-sm-10">'
            + ' <label>Extensión</lavel><input class="form-control" name="extension'+a+'" type="text"/></div></div>';
        document.getElementById('archivo').appendChild(div);document.getElementById('archivo').appendChild(div);


    }
</script>

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


      <form class="form-group" method="POST" action="{{dirname($_SERVER['PHP_SELF']) . '/adminalumnos'}}" enctype="multipart/form-data">
          @csrf
          <div class="card" style="margin-left: auto; margin-right: auto; width: 60rem; margin-top: 70px; padding: 4%;">


              <div class="form-group row">
                      <label for="" class="col-sm-2 col-form-label">Número de archivos:</label>
                  <div class="col-sm-10">
                      <input type="number" min="1"  class="form-control" name="cantidad">
                  </div>
              </div>
              <div class="form-group row">
                  <label for="" class="col-sm-2 col-form-label">Nombre:</label>
                  <div class="col-sm-10">
                      <input type="text" class="form-control" name="name">
                  </div>
              </div>
              <div class="form-group row">
                  <label for="" class="col-sm-2 col-form-label">Extensión:</label>
                  <div class="col-sm-10">
                      <input type="text" class="form-control" name="extension">
                  </div>
              </div>

              <div class="form-group row">
                  <label for="" class="col-sm-2 col-form-label">Archivos:</label>
                  <div class="col-sm-10">
                      <input type="button" class="btn btn-success" id="add_archivo()" onClick="addArchivo()" value="+" />
                  </div>
              <!-- El id="canciones" indica que la función de JavaScript dejará aquí el resultado -->
              <div class="row" id="archivo">
              </div>
              </div>

              <div class="form-group row">
              <label for="" class="col-sm-2 col-form-label">Convocatoria</label>
              <div class="col-sm-10">
                  <input type="text" class="form-control" name="convocatoria">
              </div>
              </div>
                  <div class="form-group row">
                      <label for="" class="col-sm-2 col-form-label">Asignatura</label>
                      <div class="col-sm-10">
                          <input type="text" class="form-control" name="asignatura">
                      </div>
                  </div>

        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label">Fecha límite:</label>
            <div class="col-sm-10">
                <input type="datetime-local" min="<?php echo date("Y-m-d\TH:i");?>" value="<?php echo date("Y-m-d\TH:i");?>" class="form-control" name="expired_date">
            </div>




        </div>
          <div class="form-group row">
              <label for="" class="col-sm-2 col-form-label">Peso:</label>
              <div class="col-sm-10">
                  <input type="number" step="0.01" min="0.01"  max="1" placeholder="1 >= 0,01" class="form-control" name="weight">
              </div>
          </div>
          <div class="form-group row">
              <label for="" class="col-sm-2 col-form-label">Intentos:</label>
              <div class="col-sm-10">
                  <input type="number" class="form-control"  min="1" name="intentos" placeholder=">=1">
              </div>
          </div>
        <fieldset class="form-group">
            <div class="row">
                <legend class="col-form-label col-sm-2 pt-0">Lenguaje de programación:</legend>
                <div class="col-sm-10">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="lenguaje" id="c" value="c.jpg" checked>
                        <label class="form-check-label" for="">
                            C
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="lenguaje" id="java" value="java.png">
                        <label class="form-check-label" for="">
                            Java
                        </label>
                    </div>
                </div>
            </div>
        </fieldset>

          <div class="form-group row">
              <label for="" class="col-sm-2 col-form-label">Código fuente:</label>
              <div class="col-sm-10">
                  <input type="file" class="form-control" name="file">
              </div>
          </div>
              <button type="submit" class="btn btn-primary">Enviar</button>

          </div>
    </form>

@endsection
