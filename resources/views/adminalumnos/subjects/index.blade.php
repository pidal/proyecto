@extends ('layouts.template')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <section class="content">
                <div class="col-lg-12">
                    <div class="panel panel-default" style="margin-top: 100px">

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

                        @if ( Session::has('success') )
                            <div class="alert alert-success alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    <span class="sr-only">Close</span>
                                </button>
                                <strong>{{ Session::get('success') }}</strong>
                            </div>
                        @endif

                        <div class="panel-body">
                            <div class="pull-left"><h3>{{__('subjects.lists')}}</h3></div>
                            <div class="pull-right">
                                <div class="btn-group">
                                    <a href="{{ route('adminasignaturas.create') }}" class="btn btn-info">{{__('subjects.add')}}
                                        <span class="fa fa-plus"></span></a>
                                </div>
                            </div>
                            <div class="table-container">
                                <table id="mytable" class="table table-bordred table-striped">
                                    <thead>
                                    <tr style="background: #02365e; color: white">
                                        <th>{{__('subjects.name')}}</th>
                                        <th>{{__('subjects.description')}}</th>
                                        <th>{{__('subjects.grade')}}</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if($subjects->count())
                                        @foreach($subjects as $subject)
                                            <tr>
                                                <td>{{$subject->name}}</td>
                                                <td>{{$subject->description}}</td>
                                                <td>{{$subject->grade}}</td>
                                                <td>
                                                    <div style="display: flex">
                                                        <a class="btn btn-primary btn-xs m-1"
                                                           href="{{route('adminrelatedsubjects', $subject->id)}}">
                                                            <span class="fa fa-group"></span></a>

                                                        <a class="btn btn-primary btn-xs m-1"
                                                           href="{{action('AdminSubjectsController@edit', $subject->id)}}">
                                                            <span class="fa fa-pencil"></span></a>

                                                        <!--<form action="{{action('AdminSubjectsController@destroy', $subject->id)}}"
                                                              method="post">
                                                            {{csrf_field()}}
                                                            <input name="_method" type="hidden" value="DELETE">
                                                            <button class="btn btn-danger btn-xs m-1"
                                                                    type="submit"><span
                                                                        class="fa fa-trash"></span></button>
                                                        </form>-->
                                                        <!-- Modal -->

                                                        <button type="button" class="btn btn-danger btn-xs m-1" data-toggle="modal" data-target="#exampleModalCenter_{{$subject->id}}">
                                                            <span class="fa fa-trash"></span>
                                                        </button>
                                                        
                                                        <div class="modal fade" id="exampleModalCenter_{{$subject->id}}" tabindex="-1" role="dialog"
                                                             aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                                <div class="modal-content">
                                                                    <form method="post" action="{{action('AdminSubjectsController@destroy', $subject->id)}}">
                                                                        @csrf
                                                                        <div class="modal-header bg-danger" style="color: white">
                                                                            <h5 class="modal-title" id="exampleModalLongTitle">{{__('Advertencia')}}</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <p>Esta a punto de eliminar la Asignatura <b>{{$subject->name}}</b></p>
                                                                            <p>Se eliminaran todos los registros asociados a esta asignatura</p>
                                                                            <p>Incluyendo practicas y alumnos relacionados</p>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <input name="_method" type="hidden" value="DELETE">
                                                                            <input type="hidden" name="subject_id" value="{{$subject->id}}"/>
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
                                {{ $subjects->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
@endsection
