<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("select").change(function(){
            $(this).find("option:selected").each(function(){
                var optionValue = $(this).attr("value");
                if(optionValue){
                    $(".box").not("." + optionValue).hide();
                    $("." + optionValue).show();
                } else{
                    $(".box").hide();
                }
            });
        }).change();
    });
</script>

<?php $__env->startSection('content'); ?>

    <?php
        $pdfUser = json_decode(\Cookie::get('pdfUser'));
        if(isset($pdfUser) && is_array($pdfUser)){
            echo '<script type="application/javascript">';
            foreach($pdfUser as $user){
                echo "window.open('/pdfuser/$user','_blank');";
            }
            echo '</script>';
            \Cookie::queue(\Cookie::forget('pdfUser'));
        }
    ?>

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-sm-4 col-sm-offset-4" style="margin-top: 100px">
                <h2>Crear usuario/s</h2>

                <form class="form-horizontal" action="<?php echo e(route('registerInstructor')); ?>" method="post"  enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>

                    <div>
                        <label>¿Deseas crear varios usuarios?</label>
                        <select name="numero" id="numero">
                            <?php if($errors->has('file')): ?>
                            <option value="si" selected>Sí</option>
                                <option value="no">No</option>
                                <?php else: ?>
                                <option value="si">Sí</option>
                                <option value="no" selected>No</option>
                            <?php endif; ?>

                        </select>
                    </div>
                    <div class="no box" style="margin-top: 20px">
                        <div class="form-group">
                        <label for="name"><?php echo e(__('Nombre')); ?></label>
                        <input id="name" type="text" class="form-control<?php echo e($errors->has('name') ? ' is-invalid' : ''); ?>" name="name" value="<?php echo e(old('name')); ?>" requisi autofocus>
                        <?php if($errors->has('name')): ?>
                            <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($errors->first('name')); ?></strong>
                                    </span>
                        <?php endif; ?>
                    </div>
                    </div>
                    <div class="no box">
                        <div class="form-group">
                            <label for="surname"><?php echo e(__('Apellidos')); ?></label>
                            <input id="surname" type="text" class="form-control<?php echo e($errors->has('surname') ? ' is-invalid' : ''); ?>" name="surname" value="<?php echo e(old('surname')); ?>" requisi autofocus>
                            <?php if($errors->has('surname')): ?>
                                <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($errors->first('surname')); ?></strong>
                                    </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="no box">
                        <div class="form-group">
                        <label for="email"><?php echo e(__('E-Mail')); ?></label>
                            <input id="email" type="email" class="form-control<?php echo e($errors->has('email') ? ' is-invalid' : ''); ?>" name="email" value="<?php echo e(old('email')); ?>" requisi>
                            <?php if($errors->has('email')): ?>
                                <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($errors->first('email')); ?></strong>
                                    </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="no box">
                    <div class="form-group">
                        <label for="email"><?php echo e(__('DNI')); ?></label>
                            <input id="dni" type="text" class="form-control<?php echo e($errors->has('dni') ? ' is-invalid' : ''); ?>" name="dni" value="<?php echo e(old('dni')); ?>" requisi>
                            <?php if($errors->has('dni')): ?>
                                <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($errors->first('dni')); ?></strong>
                                    </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="no box">
                        <fieldset class="form-group">
                                <label for="role" ><?php echo e(__('Rol')); ?></label>
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

                        <label for="file" >El formato deberá ser .csv o .xlsx</label>
                        <input id="file" type="file" class="form-control<?php echo e($errors->has('file') ? ' is-invalid' : ''); ?>" name="file" value="<?php echo e(old('file')); ?>" requisi>
                        <?php if($errors->has('file')): ?>
                            <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($errors->first('file')); ?></strong>
                                    </span>
                        <?php endif; ?>
                    </div>

                    <div class="si box" style="margin-top: 20px">
                    <div class="form-group">
                        <button type="submit" style="margin-left: 70px; margin-top:20px; width: 200px;"class="btn btn-primary button-loading" data-loading-text="Loading...">
                            <?php echo e(__('Registrarse')); ?>

                        </button>
                    </div>
                    </div>
                        <div class="no box">
                            <div class="form-group ">
                                <button type="submit"  style="margin-left: 70px; width: 200px;" class="btn btn-primary">
                                    <?php echo e(__('Registrarse')); ?>

                                </button>
                            </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.templateProfesor', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>