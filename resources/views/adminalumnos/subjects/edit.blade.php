@extends ('layouts.template')

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
                            <h3 class="panel-title">{{__('subjects.new')}}</h3>
                        </div>
                        <div class="panel-body">
                            <div class="table-container">
                                <form method="POST" action="{{ route('adminasignaturas.update',$subject->id) }}" role="form">
                                    {{ csrf_field() }}
                                    <input name="_method" type="hidden" value="PATCH">
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label" for="name"> {{__('subjects.new_name')}} </label>
                                        <input type="text" name="name" id="name" value="{{$subject->name}}"
                                               class="col-sm-6 form-control input-sm" placeholder="{{__('subjects.new_name')}}">
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label" for="name"> {{__('subjects.new_grade')}} </label>
                                        <input type="number" name="grade" id="grade" value="{{$subject->grade}}"
                                               class="col-sm-6 form-control input-sm" placeholder="{{__('subjects.new_grade')}}">
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label" for="name"> {{__('subjects.new_description')}} </label>
                                        <textarea name="description" class="col-sm-6 form-control input-sm"
                                                  placeholder="{{__('subjects.new_description')}}">{{$subject->description}}</textarea>
                                    </div>
                                    <div class="form-group row">
                                        <a href="{{ route('adminasignaturas.index') }}"
                                           class="btn btn-primary col-sm-2 link"> {{__('subjects.back')}}</a>

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
@endsection
