<?php

namespace pfg\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use pfg\Mail\UserCreateMail;
use pfg\Models\Practica;
use pfg\Models\User;
use Illuminate\Support\Facades\DB;
use pfg\Http\Requests\StorePracticaRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Session;
use PDF;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function __construct()
	{
		$this->middleware('auth');
        $this->middleware('CheckAdmin');
	}

	public function index(Request $request)
	{
		$users = DB::table('users')->paginate(8);
		return view('layouts.usuarios', compact('users'));
	}

	public function pdfuser(Request $request){
		if(isset($request->id)){
			$user = User::where('id',$request->id)->first();
		} else {
			throw new \Exception("el id es necesario");
		}
		$email = $user->email;
		$url = url('/') . "/createUsers/restablishpass/". $user->token;
		$pdf = PDF::loadView('layouts.userFirstLogIn', compact('email', 'url', 'user'));
		$nombrePDF = "crearUsuario_" . $user->email;
		$pdf->download('userFirstLogIn.pdf');
		//$pdf->save('pdf/cambioInicial_'.$email.'.pdf');
		//Session::flash('download', 'pdf/cambioInicial_'.$email.'.pdf');

		return $pdf->stream($nombrePDF);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(StorePracticaRequest $request)
	{

		$file = $request->file('file');
		chdir('test');
		$file_name = $file->getClientOriginalName();
		$codif = "file --mime-encoding -b " . $file->getClientOriginalName() . "|tr -d '\n'";
		$codif = shell_exec($codif);
		$encode = " iconv -f $codif -t utf8 $file_name > $file_name" . "2";
		shell_exec($encode);
		$mover = "mv $file_name" . "2" . " $file_name";
		shell_exec($mover);
		$eliminar = "rm $file_name" . "2";
		shell_exec($eliminar);

		$idRol = DB::table('profesor')->where('users_id', auth()->id())->value('roles_id');
		if ($idRol == null) {
			$idRol = DB::table('alumno')->where('users_id', auth()->id())->value('roles_id');
		}

		if ($idRol == 2) {
			$practica = new Practica();
			$practica->name = request('name');
			$practica->expired_date = request('expired_date');
			$practica->weight = request('weight');
			$practica->intentos = request('intentos');
			$practica->extension = request('extension');
			$practica->package = request('name') . '-' . date('Y-m-d');
			$practica->file_execute_profesor = $request->file('file')->getClientOriginalName();
			$practica->idProfesor = auth()->id();
			$practica->idAlumno = '3';
			$practica->file_name = '-';
			$practica->save();
		} else if ($idRol == 3) {
			$archivo = Archivo::find(request('id'));
			dd($archivo);
			$archivo->file_name = $request->file('file')->getClientOriginalName();
			$archivo->save();
		}

		//válido para extensiones de c y txt
		if ($file->extension() == 'c' || 'txt') {

			/* Indicamos que queremos guardar un nuevo archivo en el disco local.
			En este caso la ruta será: '/Users/joaquin/Documents/PFG/mi-proyecto-laravel/public'
			file_get_contents: Guardar el archivo directamente, sin crear directorios.
			*/
			Storage::disk('local')->put($practica->file_execute_profesor, file_get_contents($file));

			//Cambiamos al directorio de test

			//chdir('test');

			//Compilamos el test a realizar
			shell_exec('gcc sum_test.c -I/lib/include -lcunit  -o hola');
			//Ejecutamos el archivo creado de la compilación
			$salida = shell_exec('./hola');

			printf("Tus resultados son:");
			return dd($salida);
		} else {
			return "extensión no compatible";
		}

	}

	public function cp1251_utf8($sInput)
	{
		$sOutput = "";

		for ($i = 0; $i < strlen($sInput); $i++) {
			$iAscii = ord($sInput[$i]);

			if ($iAscii >= 192 && $iAscii <= 255)
				$sOutput .= "&#" . (1040 + ($iAscii - 192)) . ";";
			else if ($iAscii == 168)
				$sOutput .= "&#" . (1025) . ";";
			else if ($iAscii == 184)
				$sOutput .= "&#" . (1105) . ";";
			else
				$sOutput .= $sInput[$i];
		}

		return $sOutput;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param int $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param int $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param int $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		//
	}


	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param array $request
	 * @return \pfg\User
	 */
	protected function createUsers(Request $request)
	{
		if ($request['numero'] == 'no') {

			$messages = [
				'surname.required'  => 'El campo apellidos es obligatorio.'
			];

			$request->validate([
				'name' => ['required', 'string', 'max:255'],
				'surname' => ['required', 'string', 'max:255'],
				'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
				'dni' => ['required', 'string', 'max:255', 'unique:users'],
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
				'dni' => $request['dni'],
				'roles_id' => $request['role'],
				'password' => Hash::make($request['password']),
				'token' => $token,
			]);

			User::crearPdf($user);

			Mail::to($user)->send(new UserCreateMail($user));

			Session::flash('success', 'Usuario/s cargados correctamente');
			return redirect('users')->withCookie(cookie('pdfUser', json_encode([$user->id]), 60));
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
							$dni_exist = User::where('dni', $value->dni)->first();
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
							if (empty($value->dni)) {
								Session::flash('error', 'Usuario/s no cargado/s. El dni de la columna: ' . $i . ' está vacío.');
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
							if ($dni_exist) {
								Session::flash('error', 'Usuario/s no cargado/s. El dni: ' . $dni_exist['dni'] . ' ya existe.');
								continue;
							}

							$token = Str::random();
							$user = User::create([
								'name' => $value->nombre,
								'surname' => $value->apellidos,
								'email' => $value->email,
								'dni' => $value->dni,
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

					return redirect('users')->withCookie(cookie('pdfUser', json_encode($usuarios_creados), 60));
				}
			}

		}
	}

	function random_password($length = 8)
	{
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
		$password = substr(str_shuffle($chars), 0, $length);
		return $password;
	}

	public function perdida(Request $request)
	{
		return view('layouts.perdida');
	}

	public function sendPerdidaEmail(request $req)
	{
		$email = $req->input('email');
		$user = DB::table('users')->where('email', 'like', $email)->first();
		if ($user) {
			$req->request->add(['token' => $user->token]);
//            Mail::to('mateos.joaquin@gamaphone.com')->send(new \App\Mail\Remember());
//            return view ('home');
			$urlPrimero = $req->Url();
			$urlPrimero = substr($urlPrimero, 0, -16);
			$invitation = "restablishpass/";
			$path = $urlPrimero . $invitation;
//            $path = 'http://127.0.0.1:8000/restablishpass/';
			$token = $req->input('token');
			$url = $path . $token;
			$email = $req->input('email');
			$archivo = fopen("txt/recuperacion_$email.txt", "w+b");
			if ($archivo == false) {
				echo "Error al crear el archivo";
			} else {
// Escribir en el archivo:
				fwrite($archivo, "Estamos recuperando contraseña de: $email\r\n");
				fwrite($archivo, "URL: $url");
// Fuerza a que se escriban los datos pendientes en el buffer:
				fflush($archivo);
			}
// Cerrar el archivo:
			fclose($archivo);

			$pdf = PDF::loadView('passPDF', compact('email', 'url', 'user'));
			$nombrePDF = "olvidar_" . $email;
			return $pdf->stream($nombrePDF);
			Session::flash('success', '¡Correo de restauración de contraseña enviado correctamente!');

			return redirect('/');

		} else {
			Session::flash('error', '¡El e-mail no existe!');
			return redirect('perdida');
		}
	}

}
