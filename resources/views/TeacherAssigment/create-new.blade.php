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
					@for($i = 1; $i<=old('number_files_delivered'); $i++)
	                    @php
	                        $filename = "fileName_$i";
	                        $weight = "weight_$i"
	                    @endphp
						<div class="row file" style="margin-top: 20px;">
							<div class="col-md-6">
								<label>Nombre de archivo {{ $i }} a entregar y extensión:</label>
								<input class="form-control" name="fileName_{{ $i }}" id="fileName_{{ $i }}" type="text" placeholder="Ej) practica.c" value="{{ old($filename)}}" required="">
							</div>
							<div class="col-md-6">
								<label>Ponderación del archivo {{ $i }}:</label>
								<input class="form-control" id="weight_{{ $i }}" name="weight_{{ $i }}" type="number" min="1" max="100" placeholder="100%" value="{{ old($weight) }}" required="">
							</div>
						</div>      
                   	@endfor
				</div>

				<div class="row">
					<div class="col-md-12">
						<div class="form-group" style="margin-top: 20px">
							<label for="type">{{ __('Tipo de práctica: ') }}</label>
							<div>
				                <select name="type" id="type" class="form-control">
				                    <option value="individual" @if(old('type') == 'individual') selected @endif>Individual</option>
				                    <option value="grupo" @if(old('type') == 'grupo') selected @endif>Grupo</option>
				                </select>
				                @if ($errors->has('type'))
				                    <span class="invalid-feedback" role="alert">
				                        <strong>{{ $errors->first('type') }}</strong>
				                    </span>
				                @endif
				            </div>
				        </div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-12">
						<div class="form-group" id="grupos" style="margin-top: 20px;display: none;">
							<label for="members_number">{{ __('¿Cuántas personas van a formar el grupo?') }}</label>
							<input type="number" class="form-control" min='1' placeholder=">=1" id="members_number" name="members_number" value="{{ old('members_number')}}" >
				            @if ($errors->has('members_number'))
				                <span class="invalid-feedback" role="alert">
				                    <strong>{{ $errors->first('members_number') }}</strong>
				                </span>
				            @endif
						</div>
						
					</div>
					
				</div>

				<div class="form-group row" style="margin-top: 50px;">
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
	$('#number_files_delivered').change(function(e){
		e.preventDefault();
		var files = $(this).val();
		var current = $('.file').length;

		if (current < files){
			var left = files - current;
			for ($i = 1; $i <= left; $i++) {

				var row = document.createElement("div");
				row.className = 'row file';
				row.style = 'margin-top:20px';

					var file = document.createElement("div");
					file.className = 'col-md-6';

						var label_file = document.createElement('label');
						label_file.innerHTML = 'Nombre de archivo ' + $i + ' a entregar y extensión:';
						file.appendChild(label_file);

						var input_file = document.createElement('input');
						input_file.className = 'form-control';
						input_file.name = 'fileName_' + $i;
	            		input_file.id = 'fileName_' + $i;
	            		input_file.type = 'text';
	            		input_file.placeholder = 'Ej) practica.c';
	            		input_file.required = true;
	            		file.appendChild(input_file);

					row.appendChild(file);

					var weight = document.createElement("div");
					weight.className = 'col-md-6';

						var label_weight = document.createElement('label');
						label_weight.innerHTML = 'Ponderación del archivo ' + $i + ':';
						weight.appendChild(label_weight);

						var input_weight = document.createElement('input');
						input_weight.className = 'form-control';
						input_weight.id = 'weight_' + $i + '';
						input_weight.name = 'weight_' + $i + '';
			            input_weight.type = 'number';
			            input_weight.min = '1'
			            input_weight.max = '100'
			            input_weight.placeholder = '100%';
			            input_weight.required = true;
			            weight.appendChild(input_weight);

					row.appendChild(weight);


				document.getElementById('files').appendChild(row);

			}
		}
		if (current > files){
			var extra = current - files;
			for($i = 1; $i <= extra; $i++){
				$('#files div.file').last().remove();
			}
		}
	});

	$('#type').change(function(){
		if ($(this).val() == 'grupo') {
			$('#grupos').show();
			$('#members_number').attr("required","required");
		}
		if ($(this).val() == 'individual') {
			$('#grupos').hide();
			$('#members_number').val('');
            $('#members_number').attr("required","false");
		}
	});

	$('#subject_id').change(function(e){
		e.preventDefault();
		var subject = $(this).val();
		$.ajax({
			type:'GET',
			url: "{{route('numberofstudentsbysubject')}}",
			data:{subject_id:subject},
			success:function(data){
				$number_students = data.number_students;
				$users = data.users;
			}
		});
	});

	$('#members_number').change(function(e){
		e.preventDefault();

		if ( $('#subject_id').val() == "" ) {
			alert("Debes seleccionar una asignatura para continuar");
			$('#members_number').val('');
			return 0;
		}

		var members = parseInt($(this).val());
        var students = $number_students;
        var groups = Math.ceil(students / members );

        alert(groups);

	});
</script>
@endsection
