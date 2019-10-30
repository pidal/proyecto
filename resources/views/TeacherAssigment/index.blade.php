@extends ('layouts.templateProfesor')

@section('content')

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-sm-8 col-sm-offset-4" style="margin-top: 100px">
                <form method="get" action="{{action('TeacherAssigmentController@index')}}">
                    <div class="form-group row">
                        <label for="subjects" class="col-sm-3 col-form-label">{{__('subjects.selectone')}}</label>
                        <div class="col-sm-6">
                            <select id="subject" name="subject" class="form-control">
                                @foreach($subjects as $subject)
                                    <option value="{{$subject->id}}"
                                            @if(Request::query('subject')==$subject->id) selected @endif >{{$subject->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <input type="submit" class="btn btn-primary" value="{{__('Buscar')}}"/>
                        </div>

                        <a class="btn btn-primary btn-xs m-1"
                           href="{{route('teacherassignmentadd')}}">
                           {{__('alumnos.add')}}
                            <span class="fa fa-plus"></span></a>
                    </div>
                </form>
                <table class="table">
                    @if ( Session::has('success') )
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                <span class="sr-only">Close</span>
                            </button>
                            <strong>{{ Session::get('success') }}</strong>
                        </div>
                    @endif
                    @if ( Session::has('error') )
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                <span class="sr-only">Close</span>
                            </button>
                            <strong>{{ Session::get('error') }}</strong>
                        </div>
                    @endif

                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <div>
                                @foreach ($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    <thead>
                        <tr style="background: #02365e; color: white">
                            <th style="text-align: center">NOMBRE</th>
                            <th style="text-align: center">LENGUAJE</th>
                            <th style="text-align: center">CONVOCATORIA</th>
                            <th style="text-align: center">ASIGNATURA</th>
                            <th style="text-align: center">FECHA ENTREGA</th>
                            <th style="text-align: center">ARCHIVO</th>
                            <th style="text-align: center"></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>

                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    @foreach ($assigments as $assigment)

                        <tr>
                            <td style="text-align: center" align="center">
                                {{$assigment->name}}
                            </td>
                            <td style="text-align: center" align="center">
                                {{$assigment->language}}
                            </td>
                            <td style="text-align: center" align="center">
                                {{$assigment->call}}
                            </td>
                            <td style="text-align: center" align="center">
                                {{$assigment->subject}}
                            </td>
                            <td style="text-align: center" align="center">
                                {{$assigment->delivered_date}}
                            </td>
                            <td style="text-align: center" align="center">
                                {{$assigment->correction_file}}
                            </td>

                            <td style="text-align: center" align="center">
                                <div style="display: flex">
                                    <a class="btn btn-primary btn-xs m-1"
                                       href="{{action('TeacherAssigmentController@edit', $assigment->id)}}">
                                        <span class="fa fa-pencil"></span>
                                    </a>
                                    <button type="button" class="btn btn-danger btn-xs m-1" data-toggle="modal" data-target="#exampleModalCenter_{{$assigment->id}}">
                                        <span class="fa fa-trash"></span>
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="exampleModalCenter_{{$assigment->id}}" tabindex="-1" role="dialog"
                                         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <form method="post" action="{{action('TeacherAssigmentController@destroy', $assigment->id)}}">
                                                    @csrf
                                                    <div class="modal-header bg-danger" style="color: white">
                                                        <h5 class="modal-title" id="exampleModalLongTitle">{{__('Advertencia')}}</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Se está procediendo a la eliminación de la práctica <b>{{$assigment->name}}</b></p>
                                                        <p>si continuas con la eliminación se borrarán todos los registros de la misma (prácticas, alumnos asociados...).</p>
                                                        <p>¿Estás seguro de continuar?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input name="_method" type="hidden" value="DELETE">
                                                        <input type="hidden" name="assinment_id" value="{{$assigment->id}}"/>
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                        <button type="submit" class="btn btn-danger">Eliminar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>


                                </div>


                            </td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>

            </div>
        </div>
        <div class="row justify-content-center">
            {{ $assigments->links() }}
        </div>
    </div>

@stop

