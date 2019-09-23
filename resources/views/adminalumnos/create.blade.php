@extends ('layouts.template')


<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $("select").change(function () {
            $(this).find("option:selected").each(function () {
                var optionValue = $(this).attr("value");
                if (optionValue) {
                    $(".box").not("." + optionValue).hide();
                    $("." + optionValue).show();
                } else {
                    $(".box").hide();
                }
            });
        }).change();
    });
</script>

@section('content')

    @php
        $pdfUser = json_decode(\Cookie::get('pdfUser'));
        if(isset($pdfUser) && is_array($pdfUser)){
            echo '<script type="application/javascript">';
            foreach($pdfUser as $user){
                echo "window.open('/pdfuser/$user','_blank');";
            }
            echo '</script>';
            \Cookie::queue(\Cookie::forget('pdfUser'));
        }
    @endphp

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-sm-4 col-sm-offset-4" style="margin-top: 100px">
                <h2>Crear usuario/s</h2>

                <form class="form-horizontal" action="{{ route('adminalumnos.store') }}" method="post"
                      enctype="multipart/form-data">
                    @csrf

                    <div>
                        <label>¿Deseas crear varios usuarios?</label>
                        <select name="numero" id="numero">
                            @if ($errors->has('file'))
                                <option value="si" selected>Sí</option>
                                <option value="no">No</option>
                            @else
                                <option value="si">Sí</option>
                                <option value="no" selected>No</option>
                            @endif

                        </select>
                    </div>
                    <div class="no box" style="margin-top: 20px">
                        <div class="form-group">
                            <label for="name">{{ __('Nombre') }}</label>
                            <input id="name" type="text"
                                   class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name"
                                   value="{{ old('name') }}" requisi autofocus>
                            @if ($errors->has('name'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>
                    <div class="no box">
                        <div class="form-group">
                            <label for="surname">{{ __('Apellidos') }}</label>
                            <input id="surname" type="text"
                                   class="form-control{{ $errors->has('surname') ? ' is-invalid' : '' }}" name="surname"
                                   value="{{ old('surname') }}" requisi autofocus>
                            @if ($errors->has('surname'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('surname') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>
                    <div class="no box">
                        <div class="form-group">
                            <label for="email">{{ __('E-Mail') }}</label>
                            <input id="email" type="email"
                                   class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
                                   value="{{ old('email') }}" requisi>
                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>
                    <div class="no box">
                        <fieldset class="form-group">
                            <label for="role">{{ __('Rol') }}</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="role" id="2" value="2" checked>
                                <label class="form-check-label" for="">
                                    Profesor
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="role" id="3" value="3">
                                <label class="form-check-label" for="">
                                    Alumno
                                </label>
                            </div>
                        </fieldset>
                    </div>


                    <div class="si box" style="margin-top: 20px">

                        <div class="alert alert-success alert-dismissible" role="alert">
                            <strong>Descargue archivo de muestra <a href="cargaUsuarios.xlsx">aqui</a></strong>
                        </div>

                        <label for="file">El formato deberá ser .csv o .xlsx</label>
                        <input id="file" type="file" class="form-control{{ $errors->has('file') ? ' is-invalid' : '' }}"
                               name="file" value="{{ old('file') }}" requisi>
                        @if ($errors->has('file'))
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('file') }}</strong>
                                    </span>
                        @endif
                    </div>

                    <div class="form-group row">

                        <a href="{{ route('adminalumnos.index') }}"
                           class="btn btn-info col-sm-2 link"> {{__('alumnos.back')}}</a>

                        <div class="si box">
                            <button type="submit" class="btn btn-primary button-loading" data-loading-text="Loading...">
                                {{ __('Registrar') }}
                            </button>
                        </div>
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
