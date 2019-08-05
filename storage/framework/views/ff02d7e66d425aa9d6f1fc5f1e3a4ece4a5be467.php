<div class="col-xs-12">
    <div class="col-sm-12 col-sm-offset-4">
        <h2>Crear práctica</h2>
        <div style="margin-top: 20px">
            <div class="form-group">
                <label for="name"><?php echo e(__('Nombre de la práctica')); ?></label>
                <input id="name" type="text"
                       required
                       class="form-control<?php echo e($errors->has('name') ? ' is-invalid' : ''); ?>"
                       name="name" value="<?php echo e(old('name')); ?>" requisi autofocus />
                    <?php if($errors->has('name')): ?>
                        <span class="invalid-feedback" role="alert">
                            <strong><?php echo e($errors->first('name')); ?></strong>
                        </span>
                    <?php endif; ?>
            </div>
        </div>
        <?php if(empty($student) || count($student) == 0 || count($student) != old('number_files_delivered')): ?>
            <div>
                <div class="form-group">
                    <label for="number_files_delivered"><?php echo e(__('Número de archivos a entregar')); ?></label>
                    <input onclick="BuildFormFields(parseInt(this.value, 10));"
                           onkeyup="BuildFormFields(parseInt(this.value, 10));"
                           id="number_files_delivered"
                           type="number"
                           class="form-control<?php echo e($errors->has('number_files_delivered') ? ' is-invalid' : ''); ?>"
                           min="1" placeholder=">=1"
                           name="number_files_delivered"
                           required="required"
                           value="<?php echo e(old('number_files_delivered')); ?>" requisi autofocus />

                        <?php if($errors->has('number_files_delivered')): ?>
                            <span class="invalid-feedback" role="alert">
                                <strong><?php echo e($errors->first('number_files_delivered')); ?></strong>
                            </span>
                        <?php endif; ?>
                </div>
            </div>

            <div id="FormFields">
                <?php if($errors->has('fileName')): ?>
                    <span class="invalid-feedback" role="alert">
                        <strong><?php echo e($errors->first('fileName')); ?></strong>
                    </span>
                <?php endif; ?>
            </div>
        <?php else: ?>

            <?php if($errors->has('number_files_delivered')): ?>
                <span class="invalid-feedback" role="alert">
                    <strong><?php echo e($errors->first('number_files_delivered')); ?></strong>
                </span>
            <?php endif; ?>

            <div>
                <div class="form-group">
                    <label for="number_files_delivered"><?php echo e(__('Número de archivos a entregar')); ?></label>
                    <input onclick="showHide(parseInt(this.value, 10));"
                           id="number_files_delivered" type="number" class="form-control"
                           min="1" placeholder=">=1" name="number_files_delivered"
                           required="required"
                           value="<?php echo e(old('number_files_delivered')); ?>" requisi autofocus/>

                    <?php if($errors->has('number_files_delivered')): ?>
                        <span class="invalid-feedback" role="alert">
                            <strong><?php echo e($errors->first('number_files_delivered')); ?></strong>
                        </span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="hidden">
                <?php for($i = 1; $i<=old('number_files_delivered'); $i++): ?>
                    <?php
                        $filename = "fileName_$i";
                        $weight = "weight_$i"
                    ?>
                    <div id="hideShow" class="form-group">
                        <label id="label1_<?php echo e($i); ?>"
                               for="fileName_<?php echo e($i); ?>"><?php echo e(__('Nombre de archivo '.$i .' a entregar y extensión:')); ?></label>
                        <input id="fileName_<?php echo e($i); ?>" type="text"
                               class="form-control" min="1" placeholder="Ej) practica.c"
                               name="fileName_<?php echo e($i); ?>"
                               value="<?php echo e(old($filename)); ?>"
                               required
                               requisi autofocus />
                        <label id="label2_<?php echo e($i); ?>"
                               for="weight_<?php echo e($i); ?>"><?php echo e(__('Ponderación del archivo '.$i .':')); ?></label>
                        <input id="weight_<?php echo e($i); ?>" type="number"
                               class="form-control" min="1" max="100" placeholder="100%"
                               name="weight_<?php echo e($i); ?>"
                               value="<?php echo e(old($weight)); ?>"
                               required
                               requisi autofocus/>
                    </div>
                <?php endfor; ?>
            </div>

            <div id="FormFields">
                <?php if($errors->has('fileName')): ?>
                    <span class="invalid-feedback" role="alert">
                        <strong><?php echo e($errors->first('fileName')); ?></strong>
                    </span>
                <?php endif; ?>
            </div>
        <?php endif; ?>

            <div class="form-group" style="margin-top: 20px">
                <label for="attempts"><?php echo e(__('Intentos')); ?></label>
                <input id="attempts" type="number"
                       class="form-control <?php echo e($errors->has('attempts') ? ' is-invalid' : ''); ?>"
                       min='1' placeholder=">=1" name="attempts" value="<?php echo e(old('attempts')); ?>"
                       required="required"
                       requisi>
                <?php if($errors->has('attempts')): ?>
                    <span class="invalid-feedback" role="alert">
                        <strong><?php echo e($errors->first('attempts')); ?></strong>
                    </span>
                <?php endif; ?>
            </div>


        <div class="form-group" style="margin-top: 20px">
            <label for="language"><?php echo e(__('Lenguaje de programación: ')); ?></label>
            <div>
                <select name="language" id="language" class="form-control" required="required">
                    <option value="c" <?php if(old('language') == 'c'): ?> selected <?php endif; ?>>C</option>
                    <option value="c#" <?php if(old('language') == 'c#'): ?> selected <?php endif; ?>>C#</option>
                    <option value="java" <?php if(old('language') == 'java'): ?> selected <?php endif; ?>>Java</option>
                </select>
                <?php if($errors->has('language')): ?>
                    <span class="invalid-feedback" role="alert">
                        <strong><?php echo e($errors->first('language')); ?></strong>
                    </span>
                <?php endif; ?>
            </div>
        </div>

        <div class="form-group" style="margin-top: 20px">
            <label for="call"><?php echo e(__('Convocatoria: ')); ?></label>
            <div>
                <select name="call" id="call" class="form-control" required="required">
                    <option value="ordinaria" <?php if(old('call') == 'ordinaria'): ?> selected <?php endif; ?>>Ordinaria</option>
                    <option value="extraordinaria" <?php if(old('call') == 'extraordinaria'): ?> selected <?php endif; ?>>Extraordinaria</option>
                </select>
                <?php if($errors->has('call')): ?>
                    <span class="invalid-feedback" role="alert">
                        <strong><?php echo e($errors->first('call')); ?></strong>
                    </span>
                <?php endif; ?>
            </div>
        </div>
        <div class="form-group" style="margin-top: 20px">
            <label for="subject_id"><?php echo e(__('Asignatura: ')); ?></label>
            <div>
                <select name="subject_id" id="subject_id" class="form-control" required>
                    <option value=""><?php echo e(__('Seleccione una')); ?></option>
                    <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject_id => $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($subject_id); ?>"
                                <?php if($subject_id == old('subject_id')): ?> selected <?php endif; ?>><?php echo e($subject); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php if($errors->has('subject_id')): ?>
                    <span class="invalid-feedback" role="alert">
                        <strong><?php echo e($errors->first('subject_id')); ?></strong>
                    </span>
                <?php endif; ?>
            </div>
        </div>

        <div style="margin-top: 20px">
            <div class="form-group ">
                <button style="margin-left: 70px; width: 200px;" class="btn btn-primary nextBtn btn-lg pull-right" type="button" ><?php echo e(__('Siguiente paso')); ?></button>
            </div>
        </div>
    </div>
</div>
