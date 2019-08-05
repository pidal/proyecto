<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="<?php echo $__env->yieldContent('keywords'); ?>">
    <meta name="author" content="<?php echo $__env->yieldContent('author'); ?>">
    <meta name="description" content="<?php echo $__env->yieldContent('description'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo $__env->yieldContent('title', 'SSM'); ?></title>


    <link rel="stylesheet" property="stylesheet" href="/css/app.css">
    <?php echo $__env->yieldContent('styles'); ?>
    <?php echo $__env->yieldContent('head'); ?>
</head>
<body background="/image/background.jpg" bgcolor="FFCECB" style="background-position: center">
<?php $__env->startSection('header'); ?>
<?php echo $__env->yieldSection(); ?>

<?php echo $__env->yieldContent('content'); ?>


<?php $__env->startSection('footer'); ?>
<?php echo $__env->yieldSection(); ?>

<script src="/js/app.js"></script>
<?php echo $__env->yieldContent('scripts'); ?>
</body>
</html>
