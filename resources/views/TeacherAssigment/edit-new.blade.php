@extends('layouts.templateProfesor')

@section('styles')
<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script src="https://bossanova.uk/jsuites/v2/jsuites.js"></script>
<link rel="stylesheet" href="https://bossanova.uk/jsuites/v2/jsuites.css" type="text/css" />

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
				<h5><span class="badge badge-pill badge-primary">1</span> Datos básicos</h5>
                <div class="row">
                	<div class="col-md-6">

                		<div class="form-group">
							<label for="name">{{ __('Nombre de la práctica') }}</label>
							<input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ $assignment->name }}" autofocus required>
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
				                	<option value="c" @if($assignment->language == 'c') selected @endif>C</option>
                    				<option value="c#" @if($assignment->language == 'c#') selected @endif>C#</option>
                    				<option value="java" @if($assignment->language == 'java') selected @endif>Java</option>
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
				                    <option value="ordinaria" @if ($assignment->call == 'ordinaria') selected @endif>Ordinaria</option>
                    				<option value="extraordinaria" @if ($assignment->call == 'extraordinaria') selected @endif>Extraordinaria</option>
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
				                                @if($subject_id == $assignment->subject_id) selected @endif>{{$subject}}</option>
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
			                <input id="attempts" type="number" class="form-control {{ $errors->has('attempts') ? ' is-invalid' : '' }}" min='1' placeholder=">=1" name="attempts" value="{{ $assignment->attempts }}" required>
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
							<input id="number_files_delivered" type="number" class="form-control" min="1" placeholder=">=1" name="number_files_delivered" value="{{ $assignment->number_files_delivered }}" required autofocus/>

		                    @if ($errors->has('number_files_delivered'))
		                        <span class="invalid-feedback" role="alert">
		                            <strong>{{ $errors->first('number_files_delivered') }}</strong>
		                        </span>
		                    @endif
						</div>
					</div>
				</div>

				<div id="files" style="margin-bottom: 20px;">
					@php $i = 1; @endphp
               		@foreach($files as $file)
						<div class="row file" style="margin-top: 20px;">
							<div class="col-md-6">
								<label>Nombre de archivo {{ $i }} a entregar y extensión:</label>
								<input class="form-control" name="fileName_{{ $i }}" id="fileName_{{ $i }}" type="text" placeholder="Ej) practica.c" value="{{ $file->fileName }}" required="">
							</div>
							<div class="col-md-6">
								<label>Ponderación del archivo {{ $i }}:</label>
								<input class="form-control" id="weight_{{ $i }}" name="weight_{{ $i }}" type="number" min="1" max="100" placeholder="100%" value="{{ $file->weight }}" required="">
							</div>
						</div>
						@php $i++; @endphp    
                   	@endforeach
				</div>

				<h5><span class="badge badge-pill badge-primary">2</span> Tipo de práctica</h5>

				<div class="row">
					<div class="col-md-12">
						<div class="form-group" style="margin-top: 20px">
							<label for="type">{{ __('Tipo de práctica: ') }}</label>
							<div>
				                <select disabled name="type" id="type" class="form-control">
									<option value="individual" @if($assignment->type == 'individual') selected @endif>Individual</option>
                    				<option value="grupo" @if($assignment->type == 'grupo') selected @endif>Grupo</option>
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
						<div id="grupos_number" class="form-group" style="margin-top: 20px;{{ $assignment->type != 'grupo'?'display: none;':''  }}">
							<label for="members_number">{{ __('¿Cuántas personas van a formar el grupo?') }}</label>
							<input disabled type="number" class="form-control" min='1' placeholder=">=1" id="members_number" name="members_number" value="{{ @$group_assignment[0]->members_number}}" >
				            @if ($errors->has('members_number'))
				                <span class="invalid-feedback" role="alert">
				                    <strong>{{ $errors->first('members_number') }}</strong>
				                </span>
				            @endif
						</div>
					</div>
				</div>

				<div id="grupos">
					@if( $assignment->type == 'grupo' )
						@php $i=1; @endphp
						@foreach($group_assignment as $group)

							<div class="row grupo" style="margin-top: 20px;">
								<div class="col-md-12"><h4>{{$group->groupName}}</h4></div>
								@php $j=1; @endphp
                            	@foreach($group->students as $student)
                            		<div class="col-md-6">
                            			<label>Introduce el nombre del componente {{$j}} del grupo {{$i}}</label>
                            			<select disabled class="form-control students" name="users_id_{{$i}}_{{$j}}">
                            				<option value="">Seleccione un Estudiante</option>
                            				@foreach($users as $user)
                                        	<option value="{{$user->id}}"
                                                @if($student->users_id == $user->id)
                                                selected
                                                @endif
                                        	>{{$user->name}}</option>
                                    @endforeach
                            			</select>
                            		</div>
                            		@php $j++; @endphp
								@endforeach
							</div>
						@endforeach
						@php $j++; @endphp
					@endif					
				</div>

				<h5><span class="badge badge-pill badge-primary">3</span> Datos de entrega</h5>

				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="attempts">{{ __('Fecha de entrega') }}</label>

							<input id="delivered_date" type="text" class="form-control{{ $errors->has('delivered_date') ? ' is-invalid' : '' }}" name="delivered_date" value="{{ $assignment->delivered_date->format('d/m/Y G:i') }}" required>
							@if ($errors->has('delivered_date'))
								<span class="invalid-feedback" role="alert">
									<strong>{{ $errors->first('delivered_date') }}</strong>
								</span>
							@endif
							<span id="invalid-date" class="invalid-feedback" role="alert" style="display: none;">
								<strong>La fecha debe ser mayor a la fecha y hora actuales.</strong>
							</span>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="attempts">{{ __('Archivo de corrección') }}</label>
							<input id="file" type="file" class="form-control {{ $errors->has('file') ? ' is-invalid' : '' }}" name="file">
							@if ($errors->has('file'))
								<span class="invalid-feedback" role="alert">
									<strong>{{ $errors->first('file') }}</strong>
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
			$('#grupos_number').show();
			$('#members_number').attr("required","required");
		}
		if ($(this).val() == 'individual') {
			$('#grupos_number').hide();
			$('#members_number').val('');
            $('#members_number').attr("required",false);
            $('#grupos').html('');
		}
	});

	$('#subject_id').change(function(e){
		getUsers($(this).val());
	});

	$('#members_number').change(function(e){
		e.preventDefault();

		if ( $('#subject_id').val() == "" ) {
			alert("Debes seleccionar una asignatura para continuar");
			$('#members_number').val('');
			return 0;
		}

		$('#grupos').html('');
		getUsers($('#subject_id').val());

		var members = parseInt($(this).val());
        var students = $number_students;
        var grupos = Math.ceil(students / members );
        var current = $('.grupo').length;

		$k = 0;
        for ($i = 1; $i <= grupos; $i++) {

        	var row = document.createElement("div");
        	row.className = 'row grupo';
			row.style = 'margin-top:20px';

				title_container = document.createElement("div");
				title_container.className = 'col-md-12';
					title = document.createElement("h4");
					title.innerHTML = 'Grupo ' + $i;
					title_container.appendChild(title);

				row.appendChild(title_container);

				for ($j = 1; $j < members + 1; $j++) {
					$k++;
					if ($k <= students) {
						var member = document.createElement("div");
						member.className = 'col-md-6';

							label = document.createElement('label');
							label.innerHTML = 'Introduce el nombre del componente ' + $j + ' del grupo ' + $i;
							member.appendChild(label);

							select = document.createElement('select');
							select.className = 'form-control students';
							select.onchange = recalculateStudents;
							select.name = 'users_id_' + $i + '_' + $j;
							select.value = '';
							select.required = true;
							member.appendChild(select);

							option = document.createElement('option');
							option.value = '';
							option.text = "Seleccione un Estudiante";
							select.appendChild(option);

							$.each($users,function(key,user){
								option = document.createElement('option');
								option.value = user.id;
								option.text = user.name;
								select.appendChild(option);
							});
						row.appendChild(member);
					}

				}
			document.getElementById('grupos').appendChild(row);
		}
	});
	
	function recalculateStudents(){
		$('.students:not([name="'+$(this).prop("name")+'"]) option[value="'+$(this).val()+'"]').remove();
	}

	function getUsers(subject){
		$.ajax({
			type:'GET',
			url: "{{route('numberofstudentsbysubject')}}",
			data:{subject_id:subject},
			success:function(data){
				$number_students = data.number_students;
				$users = data.users;
			}
		});
	}

	@if( old('subject_id') != "" )
		getUsers({{ old('subject_id') }});
	@endif

	jSuites.calendar(document.getElementById('delivered_date'), {
	    time:true,
	    format:'DD/MM/YYYY HH24:MI',
	    today:0,
	    onclose:function() {
	        if ( new Date(this.value) <=  new Date('{{ Carbon\Carbon::now() }}')) {
	            document.getElementById('delivered_date').value = '';
	            document.getElementById('delivered_date').classList.add("is-invalid");
	            document.getElementById('invalid-date').style.display = "initial";
	        }else{
	            document.getElementById('invalid-date').style.display = "none";
	            document.getElementById('delivered_date').classList.remove("is-invalid");

	        }
	    }
	});
</script>
@endsection
