<div class="col-xs-12">
    <div class="col-sm-12 col-sm-offset-4">
        <h2>Crear práctica</h2>

        <div class="form-group" style="margin-top: 20px">
            <label for="type"><?php echo e(__('Tipo de práctica: ')); ?></label>
            <div>
                <select name="type" id="type" class="form-control">
                    <option value="individual" <?php if(old('type') == 'individual'): ?> selected <?php endif; ?>>Individual</option>
                    <option value="grupo" <?php if(old('type') == 'grupo'): ?> selected <?php endif; ?>>Grupo</option>
                </select>
                <?php if($errors->has('type')): ?>
                    <span class="invalid-feedback" role="alert">
                        <strong><?php echo e($errors->first('type')); ?></strong>
                    </span>
                <?php endif; ?>
            </div>
        </div>

        <div class="form-group" id="gruposdiv" style="margin-top: 20px; display:none;">
            <label for="members_number"><?php echo e(__('¿Cuántas personas van a formar el grupo?')); ?></label>
            <input class="form-control<?php echo e($errors->has('members_number') ? ' is-invalid' : ''); ?>" min='1'
                   placeholder=">=1" id="members_number" name="members_number" value="<?php echo e(old('members_number')); ?>" >
            <?php if($errors->has('members_number')): ?>
                <span class="invalid-feedback" role="alert">
                    <strong><?php echo e($errors->first('members_number')); ?></strong>
                </span>
            <?php endif; ?>
        </div>

        <div class="grupo box">
            <div id="FormFields2">

                <?php if(old('members_number') && old('type') == 'grupo'): ?>
                    <?php $k=0; ?>
                    <?php for($i=1; $i<=old('members_number'); $i++): ?>
                        <div style="margin-top: 20px;">
                            <h2>Grupo <?php echo e($i); ?>:</h2>
                            <?php for($j=1; $j<=ceil($student['number_students']/old('members_number')); $j++): ?>
                                <?php $k++; ?>
                                <?php if($k<=$student['number_students']): ?>
                                    <label>Introduce el nombre del componente <?php echo e($j); ?> del grupo <?php echo e($i); ?></label>
                                    <select class="form-control selectable" name="users_id.<?php echo e($j); ?>.<?php echo e($i); ?>">
                                        <option value="null">Seleccione un Estudiante</option>
                                        <?php $__currentLoopData = $student['users']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($user->id); ?>"
                                                    <?php if(old("users_id_".$j."_".$i) == $user->id): ?>
                                                    selected
                                                    <?php endif; ?>
                                            ><?php echo e($user->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </div>
                    <?php endfor; ?>
                <?php endif; ?>

                <?php if($errors->has('users_id')): ?>
                    <span class="invalid-feedback" role="alert">
                        <strong><?php echo e($errors->first('users_id')); ?></strong>
                    </span>
                <?php endif; ?>
            </div>
        </div>

        <div style="margin-top: 20px">
            <div class="form-group ">
                <button style="margin-left: 70px; width: 200px;" class="btn btn-primary nextBtn btn-lg pull-right"
                        type="button"><?php echo e(__('Siguiente paso')); ?></button>
            </div>
        </div>

    </div>
</div>
