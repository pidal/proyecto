<div class="col-xs-12">
    <div class="col-sm-12 col-sm-offset-4">
        <h2>Crear práctica</h2>

        <div class="form-group" style="margin-top: 20px">
            <label for="delivered_date">{{ __('Fecha de entrega') }}</label>
            <input disabled id="delivered_date" type="datetime-local" min="<?php echo date("Y-m-d\TH:i");?>"
                   class="form-control{{ $errors->has('delivered_date') ? ' is-invalid' : '' }}"
                   name="delivered_date" value="{{ strftime('%Y-%m-%dT%H:%M:%S', strtotime($assignment->delivered_date))  }}" requisi required>
            @if ($errors->has('delivered_date'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('delivered_date') }}</strong>
                </span>
            @endif
        </div>

        <div style="margin-top: 20px">
            <label for="file">Archivo de corrección</label>
            <input disabled id="file" type="file" class="form-control {{ $errors->has('file') ? ' is-invalid' : '' }}"
                   name="file" value="{{ $assignment->correction_file }}" >
            @if ($errors->has('file'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('file') }}</strong>
                </span>
            @endif
        </div>

        <div style="margin-top: 20px">
            <div class="form-group ">
                <button class="btn btn-success btn-lg pull-right" type="submit">{{ __('Guardar cambios') }}</button>
            </div>
        </div>

    </div>
</div>