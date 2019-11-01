@extends ('layouts.templateProfesor')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-sm-8 col-sm-offset-2" style="margin-top: 140px">
                <div>
                    <!--@if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <strong>Error!</strong> Revise los campos obligatorios.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif-->
                    @if(Session::has('success'))
                        <div class="alert alert-info">
                            {{Session::get('success')}}
                        </div>
                    @endif

                    <div class="panel panel-default" >
                        <div class="panel-heading">
                            <h3 class="panel-title">{{__('subjects.new')}}</h3>
                        </div>
                        <div class="panel-body">
                            <div class="table-container">
                                <form method="POST" action="{{ route('subjects.store') }}" role="form">
                                    {{ csrf_field() }}

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label" for="name"> {{__('subjects.new_name')}} </label>
                                        <input type="text" name="name" id="name"
                                               class="col-sm-6 form-control input-sm" placeholder="{{__('subjects.new_name')}}" value="{{ old('name') }}">
                                        @if( $errors->has('name') )
                                            <span class="invalid-feedback" role="alert" style="display: unset;">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label" for="name"> {{__('subjects.new_grade')}} </label>
                                        <input type="number" name="grade" id="grade"
                                               class="col-sm-6 form-control input-sm" placeholder="{{__('subjects.new_grade')}}" value="{{ old('grade') }}">
                                        @if( $errors->has('grade') )
                                            <span class="invalid-feedback" role="alert" style="display: unset;">
                                                <strong>{{ $errors->first('grade') }}</strong>
                                            </span>
                                        @endif

                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label" for="name"> {{__('subjects.new_description')}} </label>
                                        <textarea name="description" class="col-sm-6 form-control input-sm"
                                                  placeholder="{{__('subjects.new_description')}}">{{ old('description') }}</textarea>
                                        @if( $errors->has('description') )
                                            <span class="invalid-feedback" role="alert" style="display: unset;">
                                                <strong>{{ $errors->first('description') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group row">
                                        <a href="{{ route('subjects.index') }}"
                                           class="btn btn-info col-sm-2 link"> {{__('subjects.back')}}</a>

                                            <input type="submit" value="{{__('subjects.save')}}" class="col-sm-2 btn btn-success btn-block"/>

                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


 <div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-sm-4 col-sm-offset-4" style="margin-top: 100px">
            <h2>Crear asignatura</h2>
            <form class="form-horizontal" method="POST" action="{{ route('subjects.store') }}" role="form">
                @csrf


                <div class="no box" style="margin-top: 20px">
                    <div class="form-group">
                        <label for="name">{{__('subjects.new_name')}}</label>
                        <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" autofocus>
                        @if ($errors->has('name'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="no box" style="margin-top: 20px">
                    <div class="form-group">
                        <label for="grade">{{__('subjects.new_grade')}}</label>
                        <input id="grade" type="text" class="form-control{{ $errors->has('grade') ? ' is-invalid' : '' }}" name="grade" value="{{ old('grade') }}" autofocus>
                        @if ($errors->has('grade'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('grade') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="no box" style="margin-top: 20px">
                    <div class="form-group">
                        <label for="grade">{{__('subjects.new_description')}}</label>
                        <textarea name="description" class="col-sm-6 form-control input-sm" placeholder="{{__('subjects.new_description')}}">{{ old('description') }}</textarea>
                        @if ($errors->has('description'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('description') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                 <div class="form-group row">
                    <a href="{{ url('/subjects') }}"
                           class="btn btn-info col-sm-2 link"> {{__('alumnos.back')}}</a>
                    <div class="no box">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Registrar') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
