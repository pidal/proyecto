<div class="col-xs-12">
    <div class="col-sm-12 col-sm-offset-4">
        <h2>Crear práctica</h2>

        <div class="form-group" style="margin-top: 20px">
            <label for="delivered_date"><?php echo e(__('Fecha de entrega')); ?></label>
            <input id="delivered_date" type="datetime-local" min="<?php echo date("Y-m-d\TH:i");?>"
                   class="form-control<?php echo e($errors->has('delivered_date') ? ' is-invalid' : ''); ?>"
                   name="delivered_date" value="<?php echo e(old('delivered_date')); ?>" requisi required>
            <?php if($errors->has('delivered_date')): ?>
                <span class="invalid-feedback" role="alert">
                    <strong><?php echo e($errors->first('delivered_date')); ?></strong>
                </span>
            <?php endif; ?>
        </div>

        <div style="margin-top: 20px">
            <label for="file">Archivo de corrección</label>
            <input id="file" type="file" class="form-control <?php echo e($errors->has('file') ? ' is-invalid' : ''); ?>"
                   name="file" value="<?php echo e(old('file')); ?>" requisi required>
            <?php if($errors->has('file')): ?>
                <span class="invalid-feedback" role="alert">
                    <strong><?php echo e($errors->first('file')); ?></strong>
                </span>
            <?php endif; ?>
        </div>

        <div style="margin-top: 20px">
            <div class="form-group ">
                <button class="btn btn-success btn-lg pull-right" type="submit"><?php echo e(__('Crear práctica')); ?></button>
            </div>
        </div>

    </div>
</div>
