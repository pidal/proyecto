@extends('layouts.templateProfesor')

@section('styles')

<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>

<style>

    .stepwizard-row {
        display: table-row;
    }

    .stepwizard {
        display: table;
        width: 100%;
        position: relative;
    }

    .stepwizard-step button[disabled] {
        opacity: 1 !important;
        filter: alpha(opacity=100) !important;
    }

    .stepwizard-row:before {
        top: 14px;
        bottom: 0;
        position: absolute;
        content: " ";
        width: 100%;
        height: 1px;
        background-color: #ccc;
        z-order: 0;

    }

    .stepwizard-step {
        display: table-cell;
        text-align: center;
        position: relative;
    }

    .btn-circle {
        width: 30px;
        height: 30px;
        text-align: center;
        padding: 6px 0;
        font-size: 12px;
        line-height: 1.428571429;
        border-radius: 15px;
    }
</style>


<script type="text/javascript">
    $(document).ready(function () {

        var navListItems = $('div.setup-panel div a'),
            allWells = $('.setup-content'),
            allNextBtn = $('.nextBtn'),
            mytype = $('#type'),
            gruposdiv = $('#gruposdiv'),
            selectSubject = $('#subject_id'),
            $number_students = 0,
            $users = [],
            inputMemeberNumber = $('#members_number'),
            selectable = $(".selectable"),
            FormFields2 = $("#FormFields2");

        allWells.hide();

        navListItems.click(function (e) {
            e.preventDefault();
            var $target = $($(this).attr('href')),
                $item = $(this);

            if (!$item.hasClass('disabled')) {
                navListItems.removeClass('btn-primary').addClass('btn-default');
                $item.addClass('btn-primary');
                allWells.hide();
                $target.show();
                $target.find('input:eq(0)').focus();
            }
        });

        allNextBtn.click(function () {
            var curStep = $(this).closest(".setup-content"),
                curStepBtn = curStep.attr("id"),
                nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
                curInputs = curStep.find("input[type='text'],input[type='url']"),
                isValid = true;

            $(".form-group").removeClass("has-error");



            console.log(curInputs);


            for (var i = 0; i < curInputs.length; i++) {

                if (!curInputs[i].validity.valid) {
                    isValid = false;
                    $(curInputs[i]).closest(".form-group").addClass("has-error");
                }
            }

            if (isValid)
                nextStepWizard.removeAttr('disabled').trigger('click');
        });

        mytype.change(function(event){
            if($(this).val() == 'grupo'){
                gruposdiv.show();
                $('#members_number').attr("required","required");
            } else {
                gruposdiv.hide();
                FormFields2.html('');
                $('#members_number').val('');
                $('#members_number').attr("required","false");
            }
        });

        selectSubject.change(function(event){
            event.preventDefault();
            var subject = $(this).val();
            $.ajax({
                type:'GET',
                url: "{{route('numberofstudentsbysubject')}}",
                data:{subject_id:subject},
                success:function(data){
                    $number_students = data.number_students;
                    $users = data.users;
                }
            });
        });

        inputMemeberNumber.keyup(BuildFormFields2);

        $('div.setup-panel div a.btn-primary').trigger('click');

        function recalculateUsersSelect(){
            $('.selectable:not([name="'+$(this).prop("name")+'"]) option[value="'+$(this).val()+'"]').remove();
        }

        if(selectable)
            selectable.change(recalculateUsersSelect);

        function BuildFormFields2() {

            if($(this).val() <= 0) return;
            $amount = parseInt($(this).val());
            var alumno = $number_students;
            var groups = Math.ceil(alumno / $amount );

            var
                $container = document.getElementById('FormFields2'),
                $item, $field, $option, $campo, $i, $j, $k;

            $container.class = "form-group";

            $container.innerHTML = '';
            $k = 0;
            for ($j = 1; $j < groups + 1; $j++) {

                $item = document.createElement('div');
                $item.class = 'form-group';
                $item.style = 'margin-top:20px';

                $campo = document.createElement('h2');
                $campo.innerHTML = 'Grupo ' + $j + ':';
                $item.appendChild($campo);

                for ($i = 1; $i < $amount + 1; $i++) {
                    $k++;
                    if ($k <= alumno) {

                        $field = document.createElement('label');
                        $field.innerHTML = 'Introduce el nombre del componente ' + $i + ' del grupo ' + $j;
                        $item.appendChild($field);
                        $field = document.createElement('select');
                        $field.className = 'form-control selectable';
                        $field.name = 'users_id.' + $j + '.' + $i;
                        $field.type = 'text';
                        $field.onchange = recalculateUsersSelect;
                        $field.value = '';
                        $item.appendChild($field);

                        //first option
                        $option = document.createElement('option');
                        $option.value = null;
                        $option.text = "{{__("Seleccione un Estudiante")}}";
                        $field.appendChild($option);

                        $.each($users,function(key,user){
                            $option = document.createElement('option');
                            $option.value = user.id;
                            $option.text = user.name;
                            $field.appendChild($option);
                        });
                        $container.appendChild($item);
                    }
                }
            }
        }

        @if(old('subject_id'))
            selectSubject.trigger('change');
        @endif

        @if(old('type'))
            mytype.trigger('change');
        @endif

    });

    function BuildFormFields($amount) {

        var
            $container = document.getElementById('FormFields'),
            $item, $field, $i;
        $container.class = "form-group";

        $container.innerHTML = '';

        for ($i = 1; $i < $amount + 1; $i++) {
            $item = document.createElement('div');
            $item.class = 'form-group';
            $item.style = 'margin-top:20px';

            $field = document.createElement('label');
            $field.innerHTML = 'Nombre de archivo ' + $i + ' a entregar y extensión:';
            $item.appendChild($field);

            $field = document.createElement('input');
            $field.className = 'form-control';
            $field.name = 'fileName.' + $i;
            $field.id = 'fileName.' + $i;
            $field.type = 'text';
            $field.placeholder = 'Ej) practica.c';
            $field.required = true;
            $item.appendChild($field);

            $field = document.createElement('label');
            $field.innerHTML = 'Ponderación del archivo ' + $i + ':';
            $item.appendChild($field);

            $field = document.createElement('input');
            $field.className = 'form-control';
            $field.name = 'weight.' + $i + '';
            $field.type = 'number';
            $field.min = '1'
            $field.max = '100'
            $field.placeholder = '100%';
            $field.required = true;
            $item.appendChild($field);
            $container.appendChild($item);
        }
    }

</script>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-sm-4 col-sm-offset-4" style="margin-top: 140px">
                <div class="stepwizard">
                    <div class="stepwizard-row setup-panel">
                        <div class="stepwizard-step">
                            <a href="#step-1" type="button" class="btn btn-primary btn-circle">1</a>
                            <p>Paso 1</p>
                        </div>
                        <div class="stepwizard-step">
                            <a href="#step-2" type="button" class="btn btn-default btn-circle" disabled="disabled">2</a>
                            <p>Paso 2</p>
                        </div>
                        <div class="stepwizard-step">
                            <a href="#step-3" type="button" class="btn btn-default btn-circle" disabled="disabled">3</a>
                            <p>Paso 3</p>
                        </div>
                    </div>
                </div>

                @if ( Session::has('error') )
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            <span class="sr-only">Close</span>
                        </button>
                        <strong>{{ Session::get('error') }}</strong>
                    </div>
                @endif

                @if ( Session::has('success') )
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            <span class="sr-only">Close</span>
                        </button>
                        <strong>{{ Session::get('success') }}</strong>
                    </div>
                @endif

                <form id="form_" class="form-horizontal was-validated" action="{{ route('teacherassignmentcreate') }}" method="post"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="row setup-content justify-content-center" id="step-1">
                        @include('TeacherAssigment.create.createAssignment-Step1')
                    </div>
                    <div class="row setup-content justify-content-center" id="step-2">
                        @include('TeacherAssigment.create.createAssignment-Step2')
                    </div>
                    <div class="row setup-content justify-content-center" id="step-3">
                        @include('TeacherAssigment.create.createAssignment-Step3')
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
