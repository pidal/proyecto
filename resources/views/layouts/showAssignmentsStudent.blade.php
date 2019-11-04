@extends('layouts.templateStudent')

@section('styles')
<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.min.css" rel="stylesheet"
      id="bootstrap-css">
@endsection

@section('content')

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-sm-6 col-sm-offset-2" style="margin-top: 140px; margin-left: 150px; margin-right: 150px;">

                <?php if(count($assignments) == 0): ?>
                <div class="alert alert-danger" role="alert">
                    <strong>No existen prácticas</strong>
                </div>
                <?php else: ?>
                <h2>Prácticas de la asignatura de {{$subject->name}}</h2>
                <?php endif; ?>


                <?php $i = 0?>

                <?php  foreach ($assignments as $assignment): ?>


                <?php  $i = $i + 1; ?>
                <div style="overflow: hidden; border: 2px solid #ccc;   text-align: left; background-color: #fafafa;">
                    <h2><?php echo $i?>. <?php echo $assignment->name;?></h2>
                    <p style="font-size: 20px"><u><b><?php echo $assignment->call;?></b></u></p><br>
                    <p><b>Fecha de entrega máxima: </b><?php echo $assignment->delivered_date;?></p>
                    <p><b>Número de ficheros a entregar</b> <?php echo $assignment->number_files_delivered;?></p>
                    <p><b>Archivo del profesor:</b> <?php echo $assignment->correction_file;?></p>
                    <p><b>Intentos:</b> <?php echo $assignment->attempts;?></p>
                    <p><b>Lenguaje:</b> <?php echo $assignment->language;?></p>
                    <p><b>Tipo de práctica:</b> <?php echo $assignment->type;?></p>

                    <form action="{{ route('showStudentsFiles',array('subject_id' => $subject->id, 'assignment_id' => $assignment->id )) }}" method="post">
                        @csrf
                        <input type="hidden" name="subject_id" value="<?php echo $subject->id;?>">
                        <input type="hidden" name="assignment_id" value="<?php echo $assignment->id;?>">

                        <input type="hidden" name="type" value="<?php echo $assignment->type;?>">

                        <button type="submit" class="btn btn-info">
                            <a class="edit" data-toggle="tooltip">
                                <i style="vertical-align: middle;" class="material-icons">Ver archivos</i>
                            </a>
                        </button>
                    </form>
                </div>

                <?php endforeach;?>

            </div>
        </div>
        <div class="row justify-content-center">
            {{$assignments->links()}}
        </div>
    </div>

@endsection
