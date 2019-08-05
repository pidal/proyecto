<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <section class="content">
                <div class="col-lg-12">
                    <div class="panel panel-default" style="margin-top: 100px">

                        <?php if( Session::has('error') ): ?>
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert"
                                        aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    <span class="sr-only">Close</span>
                                </button>
                                <strong><?php echo e(Session::get('error')); ?></strong>
                            </div>
                        <?php endif; ?>

                        <?php if( Session::has('success') ): ?>
                            <div class="alert alert-success alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    <span class="sr-only">Close</span>
                                </button>
                                <strong><?php echo e(Session::get('success')); ?></strong>
                            </div>
                        <?php endif; ?>

                        <div class="panel-body">
                            <div class="pull-left"><h3><?php echo e(__('subjects.lists')); ?></h3></div>
                            <div class="pull-right">
                                <div class="btn-group">
                                    <a href="<?php echo e(route('subjects.create')); ?>" class="btn btn-info"><?php echo e(__('subjects.add')); ?>

                                        <span class="fa fa-plus"></span></a>
                                </div>
                            </div>
                            <div class="table-container">
                                <table id="mytable" class="table table-bordred table-striped">
                                    <thead>
                                        <th><?php echo e(__('subjects.name')); ?></th>
                                        <th><?php echo e(__('subjects.description')); ?></th>
                                        <th><?php echo e(__('subjects.grade')); ?></th>
                                        <th></th>
                                    </thead>
                                    <tbody>
                                        <?php if($subjects->count()): ?>
                                            <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><?php echo e($subject->name); ?></td>
                                                    <td><?php echo e($subject->description); ?></td>
                                                    <td><?php echo e($subject->grade); ?></td>
                                                    <td>
                                                        <div style="display: flex">
                                                            <a class="btn btn-primary btn-xs m-1"
                                                               href="<?php echo e(route('relateSubjects', $subject->id)); ?>">
                                                                <span class="fa fa-group"></span></a>

                                                            <a class="btn btn-primary btn-xs m-1"
                                                               href="<?php echo e(action('SubjectsController@edit', $subject->id)); ?>">
                                                                <span class="fa fa-pencil"></span></a>

                                                            <form action="<?php echo e(action('SubjectsController@destroy', $subject->id)); ?>"
                                                                  method="post">
                                                                <?php echo e(csrf_field()); ?>

                                                                <input name="_method" type="hidden" value="DELETE">
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
                                <?php echo e($subjects->links()); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.templateProfesor', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>