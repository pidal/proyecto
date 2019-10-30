@extends('layouts.templateStudent')

@section('styles')
<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.min.css" rel="stylesheet"
      id="bootstrap-css">
@endsection

@section('content')

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-sm-4 col-sm-offset-4" style="margin-top: 140px; margin-left: 150px; margin-right: 150px;">

                <?php if(count($studentsFiles) == 0): ?>
                <div class="alert alert-danger" role="alert">
                    <strong>No existen prácticas</strong>
                </div>
                <?php else: ?>
                <h2>Práctica {{$assignment->name}} de la asignatura de: {{$subject->name}}</h2>
                <?php endif; ?>
                @if ( Session::has('error') )
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            <span class="sr-only">Close</span>
                        </button>
                        <strong>{{ Session::get('error') }}</strong>
                    </div>
                @endif

                <?php $i = 0?>
                <form class="form-horizontal" action="{{ route('sendStudentFiles',['assignment_id' => $assignment->id, 'subject_id' => $subject->id]) }}" method="post"
                      enctype="multipart/form-data">
                    @csrf

                    <?php  foreach ($studentsFiles as $studentFile):?>

                    <?php  $i = $i + 1; ?>
                    <div style="overflow: hidden; border: 2px solid #ccc;   text-align: left; background-color: #fafafa;">
                        <h2><?php echo $i?>. <?php echo $studentFile->fileName;?></h2>
                        <p style="font-size: 20px"><u><b><?php echo $studentFile->fileName;?></b></u></p><br>
                        <p><b>Ponderación del archivo: </b><?php echo $studentFile->weight;?></p>
                        <p><b>Intentos restantes:</b> <?php echo $studentFile->left_attempts;?></p>
                        <div style="margin-top: 20px">
                            <div class="form-group" style="margin-top: 20px">
                                <label for="file{{$studentFile->id}}">Archivo del alumno:</label>
                                <input id="file{{$studentFile->id}}" style="height: 43px; line-height: 25px" type="file"
                                       class="form-control{{ $errors &&  array_key_exists('file'.$studentFile->id, $errors) ? ' is-invalid' : '' }}"
                                       name="file{{$studentFile->id}}" value="{{ old('file') }}" requisi>
                                @if ($errors &&  array_key_exists('file'.$studentFile->id, $errors))
                                    <div class="alert alert-danger">
                                        <strong>{{ $errors->first('file'.$studentFile->id) }}</strong>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="files_id[]" value="{{$studentFile->id}}">
                    <input type="hidden" name="files_name[]" value="{{$studentFile->fileName}}">
                    <input type="hidden" name="group_assignment_id" value="<?php echo $studentFile->group_id?>">
                    <?php endforeach;?>
                    <button style="margin-top: 20px" type="submit" style="color: black" class="btn btn-primary">
                        {{ __('Enviar práctica') }}
                    </button>
                </form>

            </div>
        </div>
    </div>

@endsection
