@extends ('layouts.templateProfesor')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-sm-8 col-sm-offset-2" style="margin-top: 140px">
                <div>
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <strong>Error!</strong> Revise los campos obligatorios.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if(Session::has('success'))
                        <div class="alert alert-info">
                            {{Session::get('success')}}
                        </div>
                    @endif

                    <div class="panel panel-default" >
                        <div class="panel-heading">
                            <h3 class="panel-title">{{__('alumnos.new')}}</h3>
                        </div>
                        <div class="panel-body">
                            <div class="table-container">
                                <form method="POST" action="{{ route('adminalumnos.update',$alumno->id) }}" role="form">
                                    {{ csrf_field() }}
                                    <input name="_method" type="hidden" value="PATCH">
                                    <input name="roles_id" type="hidden" value="@php echo \pfg\Http\Controllers\AlumnosController::ROLE_ALUMNO @endphp">

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label" for="name"> {{__('alumnos.email')}} </label>
                                        <input type="email" name="email" id="email" value="{{$alumno->email}}"
                                               class="col-sm-6 form-control input-sm" placeholder="{{__('alumnos.email')}}">
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label" for="name"> {{__('alumnos.name')}} </label>
                                        <input type="text" name="name" id="name" value="{{$alumno->name}}"
                                               class="col-sm-6 form-control input-sm" placeholder="{{__('alumnos.name')}}">
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label" for="name"> {{__('alumnos.surname')}} </label>
                                        <input type="text" name="surname" id="surname" value="{{$alumno->surname}}"
                                               class="col-sm-6 form-control input-sm" placeholder="{{__('alumnos.surname')}}">
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label" for="name"> {{__('alumnos.dni')}} </label>
                                        <input type="text" name="dni" id="dni" value="{{$alumno->dni}}"
                                               class="col-sm-6 form-control input-sm" placeholder="{{__('alumnos.dni')}}">
                                    </div>

                                    <div class="form-group row">
                                        <a href="{{ route('alumnos.index') }}"
                                           class="btn btn-primary col-sm-2 link"> {{__('alumnos.back')}}</a>
                                            <input type="submit" value="{{__('alumnos.save')}}" class="col-sm-2 btn btn-success btn-block"/>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
