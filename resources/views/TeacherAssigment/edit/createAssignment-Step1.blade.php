<div class="col-xs-12">
    <div class="col-sm-12 col-sm-offset-4">
        <h2>Crear práctica</h2>
        <div style="margin-top: 20px">
            <div class="form-group">
                <label for="name">{{ __('Nombre de la práctica') }}</label>
                <input id="name" type="text"
                       required
                       class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                       name="name" value="{{ $assignment->name }}" required autofocus />
                    @if ($errors->has('name'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
            </div>
        </div>
        @if (empty($student) || count($student) == 0 || count($student) != $assignment->number_files_delivered)
            <div>
                <div class="form-group">
                    <label for="number_files_delivered">{{ __('Número de archivos a entregar') }}</label>
                    <input onclick="BuildFormFields(parseInt(this.value, 10));"
                           onkeyup="BuildFormFields(parseInt(this.value, 10));"
                           id="number_files_delivered"
                           type="number"
                           class="form-control{{ $errors->has('number_files_delivered') ? ' is-invalid' : '' }}"
                           min="1" placeholder=">=1"
                           name="number_files_delivered"
                           required="required"
                           value="{{ $assignment->number_files_delivered }}" required autofocus />

                        @if ($errors->has('number_files_delivered'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('number_files_delivered') }}</strong>
                            </span>
                        @endif
                </div>
            </div>

            <div id="FormFields">
                @if ($errors->has('fileName'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('fileName') }}</strong>
                    </span>
                @endif
            </div>
        @else

            @if ($errors->has('number_files_delivered'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('number_files_delivered') }}</strong>
                </span>
            @endif

            <div>
                <div class="form-group">
                    <label for="number_files_delivered">{{ __('Número de archivos a entregar') }}</label>
                    <input onclick="showHide(parseInt(this.value, 10));"
                           id="number_files_delivered" type="number" class="form-control"
                           min="1" placeholder=">=1" name="number_files_delivered"
                           required="required"
                           value="{{ $assignment->number_files_delivered }}" required autofocus/>

                    @if ($errors->has('number_files_delivered'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('number_files_delivered') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="hidden">
                @php $i = 1; @endphp
                @foreach($files as $file)

                <div id="hideShow" class="form-group">
                    <label id="">
                        
                    </label>
                    
                </div>

                @endforeach
                <!--
                @for($i; $i<= $assignment->number_files_delivered; $i++)
                    @php
                        $filename = "fileName_$i";
                        $weight = "weight_$i"
                    @endphp
                    <div id="hideShow" class="form-group">
                        <label id="label1_{{$i}}"
                               for="fileName_{{$i}}">{{ __('Nombre de archivo '.$i .' a entregar y extensión:') }}</label>
                        <input id="fileName_{{$i}}" type="text"
                               class="form-control" min="1" placeholder="Ej) practica.c"
                               name="fileName_{{$i}}"
                               value="{{ old($filename)}}"
                               required
                               required autofocus />
                        <label id="label2_{{$i}}"
                               for="weight_{{$i}}">{{ __('Ponderación del archivo '.$i .':') }}</label>
                        <input id="weight_{{$i}}" type="number"
                               class="form-control" min="1" max="100" placeholder="100%"
                               name="weight_{{$i}}"
                               value="{{ old($weight) }}"
                               required
                               required autofocus/>
                    </div>
                @endfor -->
            </div>

            <div id="FormFields">
                @if ($errors->has('fileName'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('fileName') }}</strong>
                    </span>
                @endif
            </div>
        @endif

            <div class="form-group" style="margin-top: 20px">
                <label for="attempts">{{ __('Intentos') }}</label>
                <input id="attempts" type="number"
                       class="form-control {{ $errors->has('attempts') ? ' is-invalid' : '' }}"
                       min='1' placeholder=">=1" name="attempts" value="{{ $assignment->attempts }}"
                       required="required"
                       required>
                @if ($errors->has('attempts'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('attempts') }}</strong>
                    </span>
                @endif
            </div>


        <div class="form-group" style="margin-top: 20px">
            <label for="language">{{ __('Lenguaje de programación: ') }}</label>
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

        <div class="form-group" style="margin-top: 20px">
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
        <div class="form-group" style="margin-top: 20px">
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

        <div style="margin-top: 20px">
            <div class="form-group ">
                <button style="margin-left: 70px; width: 200px;" class="btn btn-primary nextBtn btn-lg pull-right" type="button" >{{ __('Siguiente paso') }}</button>
            </div>
        </div>
    </div>
</div>
