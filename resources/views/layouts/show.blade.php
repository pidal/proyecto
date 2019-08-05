@extends ('layouts.template')



@section('content')


    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-sm-4 col-sm-offset-4" style="margin-top: 100px">
                <table class="table">

                    <thead>
                    <tr style="background: #02365e; color: white">
                        <th style="text-align: center">APELLIDOS,NOMBRE</th>
                        <th style="text-align: center">EMAIL</th>
                        <th style="text-align: center">ROL</th>
                    </tr>
                    </thead>
                    <tbody>

                    <input type = "hidden" name="_token" value="{{csrf_token()}}">


                    <tr id="1">
                        <td style="text-align: center"  align="center"  data-title="<?php echo "NOMBRE"?>"><?php echo '1' ;?>,<?php echo '1' ;?></td>
                        <td style="text-align: center"  align="center"  data-title="<?php echo "EMAIL"?>"><?php echo '1';?></td>
                        <td style="text-align: center" align="center"  data-title="<?php echo "ROL"?>"><?php echo '1';?></td>
                    </tr>
                    </tbody>

                </table>


            </div>
        </div>
    </div>



@stop

