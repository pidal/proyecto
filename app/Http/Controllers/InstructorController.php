<?php

namespace pfg\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use pfg\Mail\UserCreateMail;
use pfg\Models\Assignment;
use pfg\Models\GroupAssignment;
use pfg\Models\RelUsersGroup;
use pfg\Models\StudentFile;
use pfg\Models\User;
use Session;
use Illuminate\Support\Facades\Storage;


class InstructorController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('CheckTeacher');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }


    public function register(Request $request)
    {
        return view('auth.registerInstructor');
    }

    public function users(Request $request)
    {
        $users = DB::table('users')
            ->select('users.*')
            ->join('rel_users_subject', 'users.id', '=', 'rel_users_subject.users_id')
            ->orderBy('users.id');

        if (isset($_GET['subject'])) {
            $users->where('rel_users_subject.subject_id', $_GET['subject']);
        }

        $users = $users->paginate(8);
        $subjects = DB::table('subject')->get();
        return view('layouts.usuariosInstructor', compact('users', 'subjects'));
    }

    public function showSubjects()
    {
        $subjects = DB::table('rel_users_subject')->join('subject', 'subject_id', '=', 'subject.id')->where('rel_users_subject.users_id', auth()->id())->pluck('subject.name', 'subject.id');
        return view('layouts.showSubjects', compact('subjects'));
    }

    public function showAssignments(Request $request)
    {
        $assignments = DB::table('assignment')
            ->where('created_by', auth()->id())
            ->where('subject_id', $request->subject_id)
            ->paginate(1);
        $subject = DB::table('subject')->where('id', $request['subject_id'])->value('name');
        return view('layouts.showAssignments', compact('assignments', 'subject'));
    }


    public function selectedSubject($request)
    {
        $assignments = DB::table('assignment')->where('created_by', auth()->id())->where('subject_id', $request)->paginate(1);
        $subject = DB::table('subject')->where('id', $request)->value('name');
        //  dd($assignments);
        return view('layouts.showAssignments', compact('assignments', $assignments, 'subject', $subject));
    }


    /**
     * Show the step 1 Form for creating a new product.
     *
     * @return \Illuminate\Http\Response
     */
    public function createAssignment(Request $request)
    {
        $subjects = DB::table('rel_users_subject')
            ->join('subject', 'rel_users_subject.subject_id', '=', 'subject.id')
            ->where('rel_users_subject.users_id', auth()->id())
            ->pluck('subject.name', 'subject.id');

        $users = DB::table('users')
            ->join('rel_users_subject', 'users.id', '=', 'rel_users_subject.users_id')
            ->where('users.roles_id', '3')
            //->where('rel_users_subject.subject_id', $assignment['subject_id'])
            ->get();

        $student = array();
        if (old('subject_id')) {
            $query = DB::table('users')
                ->join('rel_users_subject', 'users.id', '=', 'rel_users_subject.users_id')
                ->select('users.id', 'users.name')
                ->where('rel_users_subject.subject_id', old('subject_id'))
                ->get();
            $student = array('number_students' => count($query), 'users' => $query);
        }
        return view('layouts.createAssignment', compact('subjects', 'student', 'users'));
    }


    /**
     * Post Request to store step1 info in session
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function postCreateAssignment(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required',
            'number_files_delivered' => 'required',
            'attempts' => 'required',
            'subject_id' => 'required',
            'language' => 'required',
            'call' => 'required',
            'type' => 'required',
            'file' => 'required',
            'delivered_date' => 'required'
        ]);

        $assignment = new Assignment();
        $assignment->fill($validatedData);
        $total = 0;
        for ($i = 1; $i <= $assignment->number_files_delivered; $i++) {
            $total = $total + $request->{'weight_' . $i};

            if ( $request->{'fileName_' . $i} == null ) {
                Session::flash('error', 'El nombre del fichero ' . $i . ' está vacío');
                return redirect('/createAssignment')->withInput();
            }
            if ( $request->{'weight_' . $i} == null) {
                Session::flash('error', 'La ponderación del fichero ' . $i . ' está vacío');
                return redirect('/createAssignment')->withInput();
            }

        }
        if ($total != 100) {
            Session::flash('error', 'La ponderación del fichero debe sumar 100% en total el valor actual es de ' . $total . '%');
            return redirect('/createAssignment')->withInput();
        }

        if($request->file('file'))
        {
            $assignment->correction_file = $request->file('file')->getClientOriginalName();
        } else
        {
            $assignment->correction_file = "";
        }

        $assignment->created_by = auth()->id();
        $assignment->save();

        $alumnos = 0;
        if ($request->subject_id) {
            $alumnos = DB::table('users')
                ->join('rel_users_subject', 'users.id', '=', 'rel_users_subject.users_id')
                ->select('users.id', 'users.name')
                ->where('rel_users_subject.subject_id',$request->subject_id)
                ->get();
        }
        $k = 1;
        if ($request->type == 'grupo')
        {
            $request->validate([
                'members_number' => 'required',
            ]);
            //TOTAL DE ALUMNOS DE LA ASIGNATURA DE LA PRÁCTICA / TOTAL MIEMBROS POR GRUPO
            $grupos = ceil(count($alumnos) / $request->members_number);

            for ($i = 1; $i <= $grupos; $i++)
            {
                //create group
                $groupAssignment = new GroupAssignment();
                $groupAssignment->name = 'Grupo: ' . $i;
                $groupAssignment->members_number = $request->members_number;
                $groupAssignment->assignment_id = $assignment->id;
                $groupAssignment->save();

                //create files per group
                $file = $request->file('file');
                for($j = 1; $j <= $request->number_files_delivered; $j++){
                    $studentFileSave = new StudentFile();
                    $studentFileSave->fileName = $request->{'fileName_'.$j};
                    $studentFileSave->weight = $request->{'weight_'.$j};
                    $studentFileSave->assignment_id = $assignment->id;
                    $studentFileSave->left_attempts = $assignment->attempts;
                    $studentFileSave->group_id = $groupAssignment->id;
                    $studentFileSave->save();
                    $path = storage_path(DIRECTORY_SEPARATOR.'TODO'.DIRECTORY_SEPARATOR . $studentFileSave->id . '_' . $studentFileSave->left_attempts);
                    mkdir($path);

                    $path2 = DIRECTORY_SEPARATOR . $studentFileSave->id . '_' . $studentFileSave->left_attempts;
                    Storage::put($path2 . DIRECTORY_SEPARATOR . $file->getClientOriginalName(), file_get_contents($file));
                }



                //create relUserGroup ?
                for($r = 1; $r <= $request->members_number; $r++)
                {
                    $us = 'users_id_' . $i . '_' . $r;
                    $us_id = $request->{$us};
                    if($k<=count($alumnos) && isset( $us_id )){
                        $relUsersGroupSave = new RelUsersGroup();
                        $relUsersGroupSave->users_id = $us_id;
                        $relUsersGroupSave->group_assignment_id = $groupAssignment->id;
                        $relUsersGroupSave->save();

                        $infoOK = $request->{'users_id_' . $i . '_' . $r};
                        if ($infoOK == null)
                        {
                            Session::flash('error', 'El nombre del alumno ' . $r . ' del grupo ' . $i . ' está vacío');
                            return redirect('/createAssignment')->withInput();
                        }
                        $k++;
                    }
                }

            }

        } else{

            $file = $request->file('file');
            foreach ($alumnos as $user){
                for($i = 1; $i <= $request->number_files_delivered; $i++){
                    $studentFileSave = new StudentFile();
                    $studentFileSave->fileName = $request->{'fileName_'.$i};
                    $studentFileSave->weight= $request->{'weight_'.$i};
                    $studentFileSave->assignment_id = $assignment->id;
                    $studentFileSave->left_attempts = $assignment->attempts;
                    $studentFileSave->users_id = $user->id;
                    $studentFileSave->save();
                    $path = storage_path('TODO'.DIRECTORY_SEPARATOR . $studentFileSave->id.'_'.$studentFileSave->left_attempts);
                    mkdir($path);

                    $path2 = DIRECTORY_SEPARATOR . $studentFileSave->id.'_'.$studentFileSave->left_attempts;
                    Storage::put($path2 .DIRECTORY_SEPARATOR. $file->getClientOriginalName() , file_get_contents($file));
                }
            }

        }

        Session::flash('success', 'Práctica creada correctamente');
        return redirect('/createAssignment');
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $request
     * @return \pfg\User
     */
    protected function createUsers(Request $request)
    {
        Session::flash('redirect', 'registerInstructor');
        
        if ($request['numero'] == 'no') {

            $messages = [
                'surname.required' => 'El campo apellidos es obligatorio.'
            ];

            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'surname' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users']
            ], $messages);

        }
        if ($request['numero'] == 'si') {
            $request->validate([
                'file' => 'required|file|max:5000|mimes:xlsx,csv',
            ]);
        }

        $token = Str::random();
        $request['password'] = self::random_password();
        if ($request['numero'] == 'no') {

            $user = User::create([
                'name' => $request['name'],
                'surname' => $request['surname'],
                'email' => $request['email'],
                'roles_id' => $request['role'],
                'password' => Hash::make($request['password']),
                'token' => $token,
            ]);

            User::crearPdf($user);

            Mail::to($user)->send(new UserCreateMail($user));

            Session::flash('success', 'Usuario/s cargados correctamente');
            return redirect('alumnos')->withCookie(cookie('pdfUser', json_encode([$user->id]), 60));
        } else {

            if (is_uploaded_file($_FILES['file']['tmp_name'])) {

                $extension = \File::extension($request['file']->getClientOriginalName());

                if ($extension == "xlsx" || $extension == "xls" || $extension == "csv") {

                    $path = $request['file']->getRealPath();

                    $items = \Excel::load($path, function ($reader) {
                    })->get();

                    if (!empty($items) && $items->count()) {
                        $i = 1;
                        $cantidad_usuarios_creados = 0;
                        $usuarios_creados = array();
                        $password = '1234';
                        foreach ($items as $key => $value) {
                            $email_exist = User::where('email', $value->email)->first();
                            $i++;
                            if (empty($value->nombre)) {
                                Session::flash('error', 'Usuario/s no cargado/s. El nombre de la columna: ' . $i . ' está vacío.');
                                continue;
                            }
                            if (empty($value->apellidos)) {
                                Session::flash('error', 'Usuario/s no cargado/s. Los apellidos de la columna: ' . $i . ' están vacíos.');
                                continue;
                            }
                            if (empty($value->email)) {
                                Session::flash('error', 'Usuario/s no cargado/s. El email de la columna: ' . $i . ' está vacío.');
                                continue;
                            }
                            if (empty($value->rol)) {
                                Session::flash('error', 'Usuario/s no cargado/s. El rol de la columna: ' . $i . ' está vacío.');
                                continue;
                            }
                            if ($email_exist) {
                                Session::flash('error', 'Usuario/s no cargado/s. El e-mail: ' . $email_exist['email'] . ' ya existe.');
                                continue;
                            }

                            $token = Str::random();
                            $user = User::create([
                                'name' => $value->nombre,
                                'surname' => $value->apellidos,
                                'email' => $value->email,
                                'roles_id' => $value->rol,
                                'password' => Hash::make($password),
                                'token' => $token
                            ]);
                            $cantidad_usuarios_creados++;
                            $usuarios_creados[] = $user->id;
                            User::crearPdf($user);

                            Mail::to($user)->send(new UserCreateMail($user));

                        }

                        Session::flash('success', $cantidad_usuarios_creados . ' Usuario/s cargados correctamente');
                    }

                    return redirect('usuariosInstructor')->withCookie(cookie('pdfUser', json_encode($usuarios_creados), 60));
                }
            }

        }
    }

    /**
     * Get the number of students by subjects
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajaxGetnumberStudentsBySubject(Request $request)
    {
        if (!isset($request->subject_id)) throw new \Exception('No ID');
        $users = DB::table('users')
            ->join('rel_users_subject', 'users.id', '=', 'rel_users_subject.users_id')
            ->select('users.id', 'users.name')
            ->where('rel_users_subject.subject_id', $request->subject_id)
			->where('users.roles_id', '3')
            ->get();
        return response()->json(['number_students' => count($users), 'users' => $users]);

    }

    function random_password($length = 8)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
        $password = substr(str_shuffle($chars), 0, $length);
        return $password;
    }

}
