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
            <div class="col-sm-8 col-sm-offset-4" style="margin-top: 100px">
                <form method="get" action="<?php echo e(route('usuariosInstructor')); ?>">
                    <div class="form-group row">
                        <label for="subjects" class="col-sm-3 col-form-label"><?php echo e(__('subjects.selectone')); ?></label>
                        <div class="col-sm-6">
                            <select id="subject" name="subject" class="form-control">
                                <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($subject->id); ?>"
                                            <?php if(Request::query('subject')==$subject->id): ?> selected <?php endif; ?> ><?php echo e($subject->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <input type="submit" class="btn btn-primary" value="<?php echo e(__('Buscar')); ?>"/>
                        </div>

                        <a class="btn btn-primary btn-xs m-1"
                           href="<?php echo e(route('registerInstructor')); ?>">
                            <span class="fa fa-plus"></span></a>
                    </div>
                </form>
                <table class="table">
                    <?php if( Session::has('success') ): ?>
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                <span class="sr-only">Close</span>
                            </button>
                            <strong><?php echo e(Session::get('success')); ?></strong>
                        </div>
                    <?php endif; ?>
                    <?php if( Session::has('error') ): ?>
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                <span class="sr-only">Close</span>
                            </button>
                            <strong><?php echo e(Session::get('error')); ?></strong>
                        </div>
                    <?php endif; ?>

                    <?php if(count($errors) > 0): ?>
                        <div class="alert alert-danger">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <div>
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <p><?php echo e($error); ?></p>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <thead>
                        <tr style="background: #02365e; color: white">
                            <th style="text-align: center">APELLIDOS,NOMBRE</th>
                            <th style="text-align: center">EMAIL</th>
                            <th style="text-align: center">ROL</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>

                    <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                    <?php  foreach ($users as $user):?>
                        <?php if ($user->roles_id == 1):
                            $user->roles_id = 'administrador';
                        elseif ($user->roles_id == 2):
                            $user->roles_id = 'profesor';
                        elseif ($user->roles_id == 3):
                            $user->roles_id = 'alumno';
                        endif?>

                        <tr id="<?php echo $user->id;?>">
                            <td style="text-align: center" align="center"
                                data-title="<?php echo "NOMBRE"?>"><?php echo $user->surname;?>
                                ,<?php echo $user->name;?></td>
                            <td style="text-align: center" align="center"
                                data-title="<?php echo "EMAIL"?>"><?php echo $user->email;?></td>
                            <td style="text-align: center" align="center"
                                data-title="<?php echo "ROL"?>"><?php echo $user->roles_id;?></td>
                            <td style="text-align: center" align="center">
                                <div style="display: flex">
                                    <a class="btn btn-primary btn-xs m-1"
                                       href="<?php echo e(action('AlumnosController@edit', $user->id)); ?>">
                                        <span class="fa fa-pencil"></span></a>

                                    <form action="<?php echo e(action('AlumnosController@destroy', $user->id)); ?>"
                                          method="post">
                                        <?php echo e(csrf_field()); ?>

                                        <input name="_method" type="hidden" value="DELETE">
                                        <button class="btn btn-danger btn-xs m-1" type="submit">
                                            <span class="fa fa-trash"></span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach;?>
                    </tbody>

                </table>

            </div>
        </div>
        <div class="row justify-content-center">
            <?php echo e($users->links()); ?>

        </div>
    </div>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.templateProfesor', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>