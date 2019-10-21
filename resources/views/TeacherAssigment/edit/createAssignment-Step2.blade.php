<div class="col-xs-12">
    <div class="col-sm-12 col-sm-offset-4">
        <h2>Crear práctica</h2>

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

        <div class="form-group" id="gruposdiv" style="margin-top: 20px;
            @if( $assignment->type != 'grupo')
                display:none;
            @endif"
        >
            <label for="members_number">{{ __('¿Cuántas personas van a formar el grupo?') }}</label>
            <input disabled class="form-control{{ $errors->has('members_number') ? ' is-invalid' : '' }}" min='1'
                   placeholder=">=1" id="members_number" name="members_number" value="{{ $group_assignment[0]->members_number}}" >
            @if ($errors->has('members_number'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('members_number') }}</strong>
                </span>
            @endif
        </div>

        <div class="grupo box">
            <div id="FormFields2">

                @if( $assignment->type == 'grupo')
                    @php $k=1; @endphp
                    @foreach($group_assignment as $group)
                        <div style="margin-top: 20px;">
                            <h2>{{$group->groupName}}</h2>
                            @php $j=1; @endphp
                            @foreach($group->students as $student)
                                <label>Introduce el nombre del componente {{$j}} del grupo {{$k}}</label>
                                <select disabled class="form-control selectable" name="users_id.{{$j}}.{{$k}}">
                                    <option value="null">Seleccione un Estudiante</option>
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}"
                                                @if($student->users_id == $user->id)
                                                selected
                                                @endif
                                        >{{$user->name}}</option>
                                    @endforeach
                                </select>
                                @php $j++; @endphp
                            @endforeach
                            @php $k++; @endphp
                        </div>
                    @endforeach
                    @php $k=0; @endphp
                    @for($i=1; $i<= $assignment->members_number; $i++)
                        <div style="margin-top: 20px;">
                            <h2>Grupo {{$i}}:</h2>
                            @for($j=1; $j<=ceil($student['number_students']/ $assignment->members_number); $j++)
                                @php $k++; @endphp
                                @if($k<=$student['number_students'])
                                    <label>Introduce el nombre del componente {{$j}} del grupo {{$i}}</label>
                                    <select disabled class="form-control selectable" name="users_id.{{$j}}.{{$i}}">
                                        <option value="null">Seleccione un Estudiante</option>
                                        @foreach($student['users'] as $user)
                                            <option value="{{$user->id}}"
                                                    @if(old("users_id_".$j."_".$i) == $user->id)
                                                    selected
                                                    @endif
                                            >{{$user->name}}</option>
                                        @endforeach
                                    </select>
                                @endif
                            @endfor
                        </div>
                    @endfor
                @endif

                @if ($errors->has('users_id'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('users_id') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div style="margin-top: 20px">
            <div class="form-group ">
                <button style="margin-left: 70px; width: 200px;" class="btn btn-primary nextBtn btn-lg pull-right"
                        type="button">{{ __('Siguiente paso') }}</button>
            </div>
        </div>

    </div>
</div>
