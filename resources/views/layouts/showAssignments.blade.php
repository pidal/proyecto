@extends('layouts.templateProfesor')

@section('content')


    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-sm-6 col-sm-offset-2" style="margin-top: 140px; margin-left: 150px; margin-right: 150px;">

                <?php if (count($assignments) == 0): ?>
                <div class="alert alert-danger" role="alert">
                    <strong>No existen prácticas</strong>
                </div>
                <?php else: ?>
                <h2>Prácticas de la asignatura de {{$subject}}</h2>
                <?php endif; ?>


                <?php $i = 0?>
                <?php  foreach ($assignments as $assignment):?>

                <?php $i = $i + 1;?>
                <div style="overflow: hidden; border: 2px solid #ccc;   text-align: left; background-color: #fafafa;">
                    <h2><?php echo $i?>. <?php echo $assignment->name;?></h2>
                    <p style="font-size: 20px"><u><b><?php echo $assignment->call;?></b></u></p><br>
                    <p><b>Fecha de entrega máxima: </b><?php echo $assignment->delivered_date;?></p>
                    <p><b>Número de ficheros a entregar por el
                            alumno:</b> <?php echo $assignment->number_files_delivered;?></p>
                    <p><b>Archivo del profesor:</b> <?php echo $assignment->correction_file;?></p>
                    <p><b>Intentos:</b> <?php echo $assignment->attempts;?></p>
                    <p><b>Lenguaje:</b> <?php echo $assignment->language;?></p>
                    <p><b>Tipo de práctica:</b> <?php echo $assignment->type;?></p>
                </div>
                <?php endforeach;?>

            </div>
        </div>
        <div class="row justify-content-center">
                {{ $assignments->links() }}
        </div>
    </div>



@stop
