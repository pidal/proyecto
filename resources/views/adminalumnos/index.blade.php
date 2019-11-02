@extends ('layouts.template')

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
                        <strong>{{ Session::get('success') }} </strong>
                        {{ implode(", ", $users_success) }}
                    </div>
                @endif

                <div class="panel-body">
                    <div class="pull-left"><h3>{{__('alumnos.lists')}}</h3></div>
                    <div class="pull-right">
                        <div class="btn-group">
                            <a href="{{ route('adminalumnos.create') }}" class="btn btn-info">{{__('alumnos.add')}}
                                <span class="fa fa-plus"></span></a>
                        </div>
                    </div>
                    <div class="table-container">
                        <table id="mytable" class="table table-bordred table-striped">
                            <thead>
                            <tr style="background: #02365e; color: white">
                                <th>{{__('alumnos.name')}}</th>
                                <th>{{__('alumnos.email')}}</th>
                                <th>{{__('alumnos.surname')}}</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($alumnos->count())
                                @foreach($alumnos as $alumno)
                                    <tr>
                                        <td>{{$alumno->name}}</td>
                                        <td>{{$alumno->email}}</td>
                                        <td>{{$alumno->surname}}</td>
                                        <td>
                                            <div style="display: flex">
                                                <a class="btn btn-primary btn-xs m-1"
                                                   href="{{action('AdminAlumnosController@edit', $alumno->id)}}">
                                                    <span class="fa fa-pencil"></span></a>

                                                <form action="{{action('AdminAlumnosController@destroy', $alumno->id)}}"
                                                      method="post">
                                                    {{csrf_field()}}
                                                    <input name="_method" type="hidden" value="DELETE">
                                                    <button class="btn btn-danger btn-xs m-1" type="submit">
                                                        <span class="fa fa-trash"></span>
                                                    </button>
                                                </form>
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
