<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="row justify-content-center" style="margin-top: 10%">
            <div class="col-sm-4 col-sm-offset-4">
                <h2>Service Student Management</h2>

                <?php if(count($errors) > 0): ?>
                    <div class="alert alert-danger">
                        Has introducido mal el Email y/o la contraseña .<br><br>
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
                <?php if( Session::has('error') ): ?>
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            <span class="sr-only">Close</span>
                        </button>
                        <strong><?php echo e(Session::get('error')); ?></strong>
                    </div>
                <?php endif; ?>

                <form method="post" action="<?php echo e(route('login')); ?>">
                    <?php echo csrf_field(); ?>


                    <div class="form-group">
                        <label for="email">Usuario</label>
                        <input type="email" class="form-control" id="email" placeholder="Email" name="email" value="<?php echo e(old('email')); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input type="password" class="form-control" id="password" placeholder="Password" name="password" required>
                    </div>

                    <div class="form-group">
                        <button class="btn btn-lg btn-primary btn-block" type="submit">Acceder</button>
                    </div>

                    <p class="text-center">
                        <a class="btn btn-link" href="<?php echo e(route('password.request')); ?>">¿Olvidaste tu contraseña?</a>
                    </p>

                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.inicio', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>