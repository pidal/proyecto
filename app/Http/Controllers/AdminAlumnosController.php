<?php

namespace pfg\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use pfg\Mail\UserCreateMail;
use pfg\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Session;


class AdminAlumnosController extends Controller
{

    private $request;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('CheckAdmin');
    }

    /**1
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $alumnos = User::where('roles_id','!=', 1)->orderBy('id', 'DESC')->paginate(8);
        return view('adminalumnos.index', compact('alumnos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('adminalumnos.create');
    }

    private function validateForm(){
        if ($this->request['numero'] == 'no') {

            $messages = [
                'surname.required' => 'El campo apellidos es obligatorio.'
            ];

            $this->request->validate([
                'name' => ['required', 'string', 'max:255'],
                'surname' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users']
            ], $messages);

        }
        if ($this->request['numero'] == 'si') {
            $this->request->validate([
                'file' => 'required|file|max:5000|mimes:xlsx,csv,txt',
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        Session::flash('redirect', 'adminalumnos/create');

        $this->request = $request;
        $this->validateForm();

        $token = Str::random();
        $request['password'] = self::random_password();

        $users_success = [];
        $users_errors = [];

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
            return redirect()->route('adminalumnos.index')->withCookie(cookie('pdfUser', json_encode([$user->id]), 60));
        }
        else {

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
                                Session::flash('error', 'Usuario/s no cargado/s correctamente');
                                $users_errors[] = $email_exist['email'];
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
                            $users_success[] = $user->email;
                            User::crearPdf($user);

                            Mail::to($user)->send(new UserCreateMail($user));

                        }
                    }

                    Session::flash('success', $cantidad_usuarios_creados. ' Usuario/s cargado/s correctamente ');
                    return redirect()->route('adminalumnos.index')




                    ->with('users_success', $users_success)
                    ->with('users_errors', $users_errors)



                    ->withCookie(cookie('pdfUser', json_encode($usuarios_creados), 60));
                }
            }

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \pfg\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $alumno = User::find($id);
        return view('adminalumnos.show', compact('alumno'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \pfg\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $alumno = User::find($id);

        if ($alumno->roles_id == 1 ) {
            return redirect()->back();
        }
        
        return view('adminalumnos.edit', compact('alumno'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \pfg\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($request->id)
            ],
            'surname' => 'required',
            'roles_id' => 'required|numeric'
        ]);
        User::find($id)->update($request->all());
        return redirect()->route('adminalumnos.index')->with('success', 'Registro Actualizado satisfactoriamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \pfg\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->route('adminalumnos.index')->with('success', 'Registro eliminado satisfactoriamente');
    }

    function random_password($length = 8)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
        $password = substr(str_shuffle($chars), 0, $length);
        return $password;
    }
}
