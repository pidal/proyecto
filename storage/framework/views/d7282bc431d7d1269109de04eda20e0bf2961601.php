<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="row justify-content-center" style="margin-top: 10%">
            <div class="col-sm-4 col-sm-offset-4">
                <h2>Service Student Management</h2>

                <form method="POST" action="<?php echo e(route('newpassword')); ?>">
                    <?php echo csrf_field(); ?>

                    <div class="form-group row">
                        <label for="email" class="col-md-4 col-form-label text-md-right"><?php echo e(__('auth.email')); ?></label>

                        <div class="col-md-6">
                            <input id="email" type="email"
                                   class="form-control<?php echo e($errors->has('email') ? ' is-invalid' : ''); ?>" name="email"
                                   value="<?php echo e(old('email')); ?>" required>

                            <?php if($errors->has('email')): ?>
                                <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($errors->first('email')); ?></strong>
                                    </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-2">
                            <button type="submit" class="btn btn-primary">
                                <?php echo e(__('auth.send_password')); ?>

                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.inicio', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>