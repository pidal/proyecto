<!DOCTYPE html>

<html>
<head>


    <title>PFG</title>

    <style type="text/css">

        table{

            width: 100%;

            border:1px solid black;

        }

        td, th{

            border:1px solid black;

        }

    </style>
    <link rel="stylesheet" href="/css/3.3.0.bootstrap.min.css">

</head>

<body>
<div class="header">
    <div style="border:  #02365e">
        <div class="row">
            <div class="col-xs-4 col-md-1"></div>
            <div class="col-xs-8 col-md-3">
                <img src="{{ public_path("image/background.jpg")}}" alt="" style="width: 200px; ">
            </div>
            <div class="col-xs-6 col-md-3">
                <h2 style="margin-top: 40px">
                    Simulación de email de <?php echo $user->name?> <?php echo $user->surname?>
                </h2>
            </div>
        </div>
    </div>
</div>
<table>
    <tbody>
    <tr>
        <td>
            <div class="padre" style="overflow: hidden; ">

                <p>Su usuario de SSM ha sido creado. El acceso será a través de su DNI </p>
                <p>Correo: <?php echo $email?></p>
                <p>Introduzca la contraseña para su usario a través del siguiente link</p>
                <p>URL: <?php echo $url?></p>
                <p>Las condiciones legales serán aceptadas en el momento que realiza el acceso.</p>
                <p>Condiciones:</p>
                <p>"Lorem ipsum dolor sit amet consectetur adipiscing, elit sociis placerat suspendisse viverra mattis quam, et ultricies curabitur hac facilisis. Risus conubia maecenas platea nec justo tincidunt netus hac elementum odio, parturient nisl nibh cras aliquam sollicitudin mollis dictum curae venenatis curabitur, egestas facilisi ornare rutrum nulla aptent iaculis lectus metus. Et natoque placerat etiam vehicula varius ante tellus aptent bibendum, turpis convallis torquent tincidunt feugiat nam ridiculus scelerisque libero magna, suscipit iaculis sodales faucibus gravida a eleifend mus.
                Fames congue nascetur erat montes a purus facilisi taciti, donec maecenas ultrices placerat gravida semper dignissim morbi, eget augue egestas bibendum posuere eleifend urna. Habitasse sociis ad torquent vivamus malesuada auctor class curae congue, tempor himenaeos tellus justo egestas lectus vehicula tincidunt, vel aliquet semper metus quisque libero nam id. Molestie vehicula netus pulvinar dapibus pretium platea justo tincidunt porttitor, donec ac vulputate vitae tortor leo aliquam nascetur sodales ante, per potenti tellus montes quam ad non nunc
                </p>


            </div>
        </td>

    </tr>
    </tbody>

</table>

</body>
</html>
