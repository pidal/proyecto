@extends ('layouts.templateProfesor')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-sm-8 col-sm-offset-4" style="margin-top: 100px">
                @if ( Session::has('error') )
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            <span class="sr-only">Close</span>
                        </button>
                        <strong>{{ Session::get('error') }}</strong>
                        @if( Session::has('users_errors'))
                            <ul>
                            @foreach(Session::get('users_errors') as $e_user)
                                <li>{{ $e_user }}</li>
                            @endforeach
                            </ul>
                        @endif
                    </div>
                @endif

                @if ( $errors->any() )
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            <span class="sr-only">Close</span>
                        </button>
                        <strong>{{$errors->first()}}</strong>
                    </div>
                @endif

                @if ( Session::has('success') )
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            <span class="sr-only">Close</span>
                        </button>
                        <strong>{{ Session::get('success') }}</strong>
                        @if( Session::has('users_success'))
                            <ul>
                            @foreach(Session::get('users_success') as $user)
                                <li>{{ $user }}</li>
                            @endforeach
                            </ul>
                        @endif
                    </div>
                @endif

                <div class="panel-body">
                    <div>
                        <form method="get" action="{{route('alumnos.index')}}">
                            <div class="form-group row">
                                <label for="subjects" class="col-sm-3 col-form-label">{{__('subjects.selectone')}}</label>
                                <div class="col-sm-5">
                                    <select id="subject" name="subject" class="form-control">
                                        <option value="">{{__('Seleccione una')}}</option>
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
                                   href="{{route('registerInstructor')}}">
                                   {{__('Nuevo alumno')}}
                                    <span class="fa fa-plus"></span></a>
                            </div>
                        </form>
                    </div>
                    <div class="table-container">
                        <table id="mytable" class="table table-bordred table-striped">
                            <thead>
                                <tr style="background: #02365e; color: white; text-align: center">
                                    <th>{{__('alumnos.name')}}</th>
                                    <th>{{__('alumnos.surname')}}</th>
                                    <th>{{__('alumnos.email')}}</th>
                                    <th>{{__('alumnos.rol')}}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            @if($alumnos->count())
                                @foreach($alumnos as $alumno)
                                    <tr>
                                        <td>{{$alumno->name}}</td>
                                        <td>{{$alumno->surname}}</td>
                                        <td>{{$alumno->email}}</td>
                                        <td>{{$alumno->role->name}}</td>
                                        <td>
                                            <div style="display: flex">
                                                <a class="btn btn-primary btn-xs m-1"
                                                   href="{{action('AlumnosController@edit', $alumno->id)}}">
                                                    <span class="fa fa-pencil"></span></a>

                                                <button type="button" class="btn btn-danger btn-xs m-1" data-toggle="modal" data-target="#exampleModalCenter_{{$alumno->id}}">
                                                    <span class="fa fa-trash"></span>
                                                </button>

                                                <!-- Modal -->
                                                <div class="modal fade" id="exampleModalCenter_{{$alumno->id}}" tabindex="-1" role="dialog"
                                                     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <form method="post" action="{{action('AlumnosController@destroy', $alumno->id)}}">
                                                                @csrf
                                                                <div class="modal-header bg-danger" style="color: white">
                                                                    <h5 class="modal-title" id="exampleModalLongTitle">{{__('Advertencia')}}</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>Se está procediendo a la eliminación del usuario <b>{{$alumno->name}}</b></p>
                                                                    <p>si continuas con la eliminación se borrarán todos los registros de la misma (prácticas, asignaturas asociadas...).</p>
                                                                    <p>¿Estás seguro de continuar?</p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <input name="_method" type="hidden" value="DELETE">
                                                                    <input type="hidden" name="user_id" value="{{$alumno->id}}"/>
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
                            @else
                                <tr>
                                    <td colspan="8">No hay registro !!</td>
                                </tr>
                            @endif
                            </tbody>

                        </table>

                    </div>
                    <div class="row justify-content-center">
                        {{ $alumnos->links() }}
                    </div>
                </div>
            </div>
        </div>
@endsection
