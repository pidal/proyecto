@extends ('layouts.templateProfesor')

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
                                    <a href="{{ route('subjects.create') }}" class="btn btn-info">{{__('subjects.add')}}
                                        <span class="fa fa-plus"></span></a>
                                </div>
                            </div>
                            <div class="table-container">
                                <table id="mytable" class="table table-bordred table-striped">
                                    <thead>
                                        <th>{{__('subjects.name')}}</th>
                                        <th>{{__('subjects.description')}}</th>
                                        <th>{{__('subjects.grade')}}</th>
                                        <th></th>
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
                                                               href="{{route('relateSubjects', $subject->id)}}">
                                                                <span class="fa fa-group"></span></a>

                                                            <a class="btn btn-primary btn-xs m-1"
                                                               href="{{action('SubjectsController@edit', $subject->id)}}">
                                                                <span class="fa fa-pencil"></span></a>

                                                            <form action="{{action('SubjectsController@destroy', $subject->id)}}"
                                                                  method="post">
                                                                {{csrf_field()}}
                                                                <input name="_method" type="hidden" value="DELETE">
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
                                {{ $subjects->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
@endsection
