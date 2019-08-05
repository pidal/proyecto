<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <section class="content">
                <div class="col-lg-12">
                    <div class="panel panel-default" style="margin-top: 100px">
                        <div class="panel-body">
                            <?php if(count($errors) > 0): ?>
                                <div class="alert alert-danger">
                                    <strong>Error!</strong> Revise los campos obligatorios.<br><br>
                                    <ul>
                                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li><?php echo e($error); ?></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                            <?php if(Session::has('success')): ?>
                                <div class="alert alert-info">
                                    <?php echo e(Session::get('success')); ?>

                                </div>
                            <?php endif; ?>
                                <?php if(Session::has('error')): ?>
                                    <div class="alert alert-error">
                                        <?php echo e(Session::get('error')); ?>

                                    </div>
                                <?php endif; ?>
                            <div class="pull-left"><h3><?php echo e(__('subjects.relatedList')); ?></h3></div>
                            <div class="pull-right">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
                                        <span class="fa fa-plus"></span>
                                    </button>
                                </div>
                            </div>
                            <div class="table-container">
                                <table id="mytable" class="table table-bordred table-striped">
                                    <thead>
                                    <th><?php echo e(__('subjects.name')); ?></th>
                                    <th><?php echo e(__('subjects.email')); ?></th>
                                    <th><?php echo e(__('subjects.dni')); ?></th>
                                    <th></th>
                                    </thead>
                                    <tbody>
                                    <?php if($users->count()): ?>
                                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($user->name); ?></td>
                                                <td><?php echo e($user->email); ?></td>
                                                <td><?php echo e($user->dni); ?></td>
                                                <td>
                                                    <div style="display: flex">
                                                        <form action="<?php echo e(route('relatedUserdestroy', $user->id)); ?>"
                                                              method="post">
                                                            <?php echo e(csrf_field()); ?>

                                                            <input type="hidden" value="<?php echo e($user->id); ?>" name="rel_subject_user_id" />
                                                            <input type="hidden" value="<?php echo e($subject_id); ?>" name="subject_id" />
                                                            <button class="btn btn-danger btn-xs m-1" type="submit"><span
                                                                        class="fa fa-trash"></span></button>
                                                        </form>
                                                    </div>

                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8">No hay registro !!</td>
                                        </tr>
                                    <?php endif; ?>
                                    </tbody>

                                </table>

                            </div>
                            <div class="row justify-content-center">
                                <?php echo e($users->links()); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form method="post" action="<?php echo e(route('postrelateSubjects',$subject_id)); ?>">
                    <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle"><?php echo e(__('subjects.relatedList')); ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="selectUsers"><?php echo e(__('subjects.selectUser')); ?></label>
                        <select class="form-control" id="userSelect" name="users_id">
                            <option value=""><?php echo e(__('subjects.selectAUser')); ?></option>
                            <?php $__currentLoopData = $allUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($user->id); ?>" ><?php echo e($user->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="subject_id" value="<?php echo e($subject_id); ?>" />
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
                </form>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.templateProfesor', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>