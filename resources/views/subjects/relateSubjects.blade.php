@extends ('layouts.templateProfesor')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <section class="content">
                <div class="col-lg-12">
                    <div class="panel panel-default" style="margin-top: 100px">
                        <div class="panel-body">
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
                                @if(Session::has('error'))
                                    <div class="alert alert-error">
                                        {{Session::get('error')}}
                                    </div>
                                @endif
                            <div class="pull-left"><h3>{{__('subjects.relatedList')}}: {{ $subject->name }}</h3></div>
                            <div class="pull-right">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
                                        <span class="fa fa-plus"></span>
                                    </button>
                                </div>
                            </div>
                            <div class="table-container">
                                <table id="mytable" class="table table-bordred table-striped">
                                    <thead>
                                    <th>{{__('subjects.name')}}</th>
                                    <th>{{__('subjects.email')}}</th>
                                    <th></th>
                                    </thead>
                                    <tbody>
                                    @if($users->count())
                                        @foreach($users as $user)
                                            <tr>
                                                <td>{{$user->name}}</td>
                                                <td>{{$user->email}}</td>
                                                <td>
                                                    <div style="display: flex">
                                                        <form action="{{route('relatedUserdestroy', $user->id)}}"
                                                              method="post">
                                                            {{csrf_field()}}
                                                            <input type="hidden" value="{{$user->id}}" name="rel_subject_user_id" />
                                                            <input type="hidden" value="{{$subject_id}}" name="subject_id" />
                                                            <button class="btn btn-danger btn-xs m-1" type="submit"><span
                                                                        class="fa fa-trash"></span></button>
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
                                {{ $users->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form method="post" action="{{route('postrelateSubjects',$subject_id)}}">
                    @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{__('subjects.relatedList')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="selectUsers">{{ __('subjects.selectUser')}}</label>
                        <select class="form-control" id="userSelect" name="users_id">
                            <option value="">{{ __('subjects.selectAUser')}}</option>
                            @foreach($allUsers as $user)
                                <option value="{{$user->id}}" >{{$user->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="subject_id" value="{{$subject_id}}" />
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
                </form>
            </div>
        </div>
    </div>

@endsection
