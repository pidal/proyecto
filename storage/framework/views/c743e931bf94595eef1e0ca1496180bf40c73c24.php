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
    <style>
        body {font-family: Arial, Helvetica, sans-serif;}
        * {box-sizing: border-box;}

        /* Position the navbar container inside the image */
        .menu {
            position: absolute;
            margin: 20px;
            width: auto;
        }

        /* The navbar */
        .topnav {
            overflow: hidden;
            background-color: #333;
        }

        /* Navbar links */
        .topnav a {
            float: left;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            font-size: 17px;
        }

        .topnav a:hover {
            background-color: #ddd;
            color: black;
        }

    </style>

</head>
<body background="/image/background.jpg" bgcolor="FFCECB" style="background-position: center">
<?php echo $__env->yieldContent('header'); ?>
    <div class="bg-img">
        <div class="menu">
            <div class="topnav">
                <a href="<?php echo e(url('/register')); ?>">Registrar usuario/s</a>
                <a href="<?php echo e(url('/home')); ?>">Usuarios</a>
                <a href="<?php echo e(url('/logout')); ?>">Salir</a>
            </div>
        </div>
    </div>


<?php echo $__env->yieldContent('content'); ?>

<script src="/js/app.js"></script>
<?php echo $__env->yieldContent('scripts'); ?>
</body>
</html>
