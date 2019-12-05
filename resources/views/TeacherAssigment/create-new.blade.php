@extends('layouts.templateProfesor')

@section('styles')
<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>

@endsection

@section('content')
 <div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-sm-8 col-sm-offset-2" style="margin-top: 100px">
			<h2>Crear práctica</h2>
			@if ($errors->any())
				<div class="alert alert-danger">
					<ul>
						@foreach($errors->all() as $error)
						<li>{{$error}}</li>
						@endforeach
					</ul>
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
			@if ( Session::has('error') )
				<div class="alert alert-danger alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
						<span class="sr-only">Close</span>
					</button>
					<strong>{{ Session::get('error') }}</strong>
				</div>
			@endif

			<form class="form-horizontal" method="POST" action="{{ route('teacherassignmentcreate') }}" role="form" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="new" value="1">


                <div class="row">
                	<div class="col-md-6">

                		<div class="form-group">
							<label for="name">{{ __('Nombre de la práctica') }}</label>
							<input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" autofocus required>
							@if ($errors->has('name'))
								<span class="invalid-feedback" role="alert">
									<strong>{{ $errors->first('name') }}</strong>
								</span>
							@endif
						</div>
                	</div>
                	<div class="col-md-6">
                		<div class="form-group">
							<label for="name">{{ __('Lenguaje de programación: ') }}</label>
							<div>
				                <select name="language" id="language" class="form-control" required="required">
				                    <option value="c" @if(old('language') == 'c') selected @endif>C</option>
				                    <option value="c#" @if(old('language') == 'c#') selected @endif>C#</option>
				                    <option value="java" @if(old('language') == 'java') selected @endif>Java</option>
				                </select>
				                @if ($errors->has('language'))
				                    <span class="invalid-feedback" role="alert">
				                        <strong>{{ $errors->first('language') }}</strong>
				                    </span>
				                @endif
				            </div>
						</div>
                	</div>
                </div>
				

				<div class="row">
					<div class="col-md-6">
						<div class="form-group">

							<label for="call">{{ __('Convocatoria: ') }}</label>
				            <div>
				                <select name="call" id="call" class="form-control" required="required">
				                    <option value="ordinaria" @if (old('call') == 'ordinaria') selected @endif>Ordinaria</option>
				                    <option value="extraordinaria" @if (old('call') == 'extraordinaria') selected @endif>Extraordinaria</option>
				                </select>
				                @if ($errors->has('call'))
				                    <span class="invalid-feedback" role="alert">
				                        <strong>{{ $errors->first('call') }}</strong>
				                    </span>
				                @endif
				            </div>
						</div>	
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="subject_id">{{ __('Asignatura: ') }}</label>
							<div>
				                <select name="subject_id" id="subject_id" class="form-control" required>
				                    <option value="">{{__('Seleccione una')}}</option>
				                    @foreach($subjects as $subject_id => $subject)
				                        <option value="{{$subject_id}}"
				                                @if($subject_id == old('subject_id')) selected @endif>{{$subject}}</option>
				                    @endforeach
				                </select>
				                @if ($errors->has('subject_id'))
				                    <span class="invalid-feedback" role="alert">
				                        <strong>{{ $errors->first('subject_id') }}</strong>
				                    </span>
				                @endif
				            </div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="attempts">{{ __('Intentos') }}</label>
			                <input id="attempts" type="number" class="form-control {{ $errors->has('attempts') ? ' is-invalid' : '' }}" min='1' placeholder=">=1" name="attempts" value="{{ old('attempts') }}" required>
			                @if ($errors->has('attempts'))
			                    <span class="invalid-feedback" role="alert">
			                        <strong>{{ $errors->first('attempts') }}</strong>
			                    </span>
			                @endif
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group">
							<label for="number_files_delivered">{{ __('Número de archivos a entregar') }}</label>
							<input id="number_files_delivered" type="number" class="form-control" min="1" placeholder=">=1" name="number_files_delivered" value="{{ old('number_files_delivered') }}" required autofocus/>

		                    @if ($errors->has('number_files_delivered'))
		                        <span class="invalid-feedback" role="alert">
		                            <strong>{{ $errors->first('number_files_delivered') }}</strong>
		                        </span>
		                    @endif
						</div>
					</div>
				</div>

				<div id="files">

					<div class="row file">
						<div class="col-md-6">

							<label id="label1_1" for="fileName_1">{{ __('Nombre de archivo 1 a entregar y extensión:') }}</label>
							<input id="fileName_1" type="text" class="form-control" min="1" placeholder="Ej) practica.c" name="fileName_1" value="{{ old($filename)}}" required autofocus />

						</div>
						<div class="col-md-6">

							<label id="label2_1" for="weight_1">{{ __('Ponderación del archivo 1:') }}</label>
                        	<input id="weight_1" type="number" class="form-control" min="1" max="100" placeholder="100%" name="weight_1" value="{{ old($weight) }}" required autofocus/>
                        	
						</div>
					</div>

					
				</div>










				<div class="form-group row">
                    <a href="{{ url('/teacherassignmen') }}"
                           class="btn btn-info col-sm-2 link"> {{__('alumnos.back')}}</a>
                    <div class="no box" style="margin-left: 20px;">
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


@section('scripts')

<script type="text/javascript">
	$('#number_files_delivered').change(function(){
		var files = $(this).val();
		var current = $('.file').length;

		if (current < files){
			var left = files - current;
			var block = "";
			for ($i = 0; $i < left; $i++) {

			}
		}

		if (current > files){


		}

	});


</script>



@endsection
