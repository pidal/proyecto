@extends('layouts.templateProfesor')

 <div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-sm-8 col-sm-offset-2" style="margin-top: 100px">
			<h2>Crear práctica</h2>
			@if ($errors->any())
				<div class="alert alert-danger">
					<ul>
						foreach($errors->all() as $error)
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

				<div class="form-group">
					<label for="name">{{ __('Nombre de la práctica') }}</label>
					<input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" autofocus>
					@if ($errors->has('name'))
						<span class="invalid-feedback" role="alert">
							<strong>{{ $errors->first('name') }}</strong>
						</span>
					@endif
				</div>

















            </form>



		</div>
	</div>
</div>