@extends('layouts.templateStudent')


<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.min.css" rel="stylesheet"
      id="bootstrap-css">




@section('content')


    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-sm-4 col-sm-offset-4" style="margin-top: 140px; margin-left: 150px; margin-right: 150px;">

                @if ( Session::has('success') )
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            <span class="sr-only">Close</span>
                        </button>
                        <strong>{{ Session::get('success') }}</strong>
                    </div>
                @endif
                <h2>Resultados de la práctica {{$assignment->name}} de la asignatura de {{$subject->name}}</h2>
                <?php $score = 0; ?>
                <?php $i = 0?>
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <?php  foreach ($studentsFiles as $studentFile):?>

                <?php  $i = $i + 1; ?>
                <div style="overflow: hidden; border: 2px solid #ccc;   text-align: left; background-color: #fafafa;">
                    <h2><?php echo $i?>. <?php echo $studentFile->fileName;?></h2>
                    <p style="font-size: 20px"><u><b><?php echo $studentFile->fileName;?></b></u></p>
                    <br>
                    <p><b>Ponderación del archivo: </b><?php echo $studentFile->weight . '%';?></p>
                    <p><b>Número de test ejecutados en el archivo:</b> <?php echo $studentFile->total;?></p>
                    <p><b>Número de test pasados:</b> <?php echo $studentFile->pass;?></p>
                    <p><b>Número de test fallidos:</b> <?php echo $studentFile->fails;?></p>
                    <p><b>Nota del archivo:</b> <?php echo $studentFile->score;?></p>
                </div>
                <?php $score = $studentFile->score + $score; ?>
                <?php $left_attempts = $studentFile->left_attempts; ?>
                <?php endforeach;?>
                <div style="overflow: hidden; border: 2px solid #ccc;   text-align: left; background-color: #fafafa;">
                    <h3><b><u>Nota total: {{$score}}</u></b></h3>
                    <h4>Intentos restantes: {{$left_attempts}} </h4>
                </div>

            </div>
        </div>
        <div class="row justify-content-center">
            {{$studentsFiles->links()}}
        </div>
    </div>



@stop
