@extends ('layouts.template')



@section('content')

    <?php
        /*$pdfUser = json_decode(\Cookie::get('pdfUser'));
        if(isset($pdfUser) && is_array($pdfUser)){
            echo '<script type="application/javascript">';
            foreach($pdfUser as $user){
                echo "window.open('/pdfuser/$user','_blank');";
            }
            echo '</script>';
            \Cookie::queue(\Cookie::forget('pdfUser'));
        }*/
    ?>

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-sm-4 col-sm-offset-4" style="margin-top: 100px">
                <table class="table">
                    @if ( Session::has('success') )
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                <span class="sr-only">Close</span>
                            </button>
                            <strong>{{ Session::get('success') }}</strong>
                        </div>
                    @endif
                    @if ( Session::has('error') )
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                <span class="sr-only">Close</span>
                            </button>
                            <strong>{{ Session::get('error') }}</strong>
                        </div>
                    @endif
                    @if(Session::has('download'))
                        <meta http-equiv="refresh" content="5;url={{ Session::get('download') }}">
                        {{--<meta http-equiv="refresh" content="2; URL=javascript:window.open('{{ Session::get('download') }}','_top');">--}}

                    @endif

                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <div>
                                @foreach ($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    <thead>
                    <tr style="background: #02365e; color: white">
                        <th style="text-align: center">APELIDOS,NOMBRE</th>
                        <th style="text-align: center">EMAIL</th>
                        <th style="text-align: center">ROL</th>
                    </tr>
                    </thead>
                    <tbody>

                    <input type="hidden" name="_token" value="{{csrf_token()}}">
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
                        </tr>
					<?php endforeach;?>
                    </tbody>

                </table>
                {{ $users->links() }}


            </div>
        </div>
    </div>



@stop

