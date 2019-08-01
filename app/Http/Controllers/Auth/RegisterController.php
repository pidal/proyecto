<?php

namespace pfg\Http\Controllers\Auth;

use pfg\Models\User;
use pfg\Models\Profesor;
use pfg\Models\Alumno;
use pfg\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Session;
use Excel;
use File;
use PDF;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use pfg\Http\Middleware\Authenticate;


class RegisterController extends Controller
{
	/*
	|--------------------------------------------------------------------------
	| Register Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles the registration of new users as well as their
	| validation and creation. By default this controller uses a trait to
	| provide this functionality without requiring any additional code.
	|
	*/

	use RegistersUsers;

	/**
	 * Where to redirect users after registration.
	 *
	 * @var string
	 */
	protected $redirectTo = '/';

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param array $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	protected function validator(array $data)
	{

		if ($data['numero'] == 'no') {

			$messages = [
				'surname.required'  => 'El campo apellidos es obligatorio.'
			];
			$rules = [
				'name' => ['required', 'string', 'max:255'],
				'surname' => ['required', 'string', 'max:255'],
				'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
				'dni' => ['required', 'string', 'max:255', 'unique:users'],
			];
			return Validator::make($data, $rules,$messages);
		}
		if ($data['numero'] == 'si') {
			return Validator::make($data, [
				'file' => 'required|file|max:5000|mimes:xlsx,csv',
			]);
		}
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param array $data
	 * @return \pfg\User
	 */
	protected function create(array $data)
	{


		$data['password'] = self::random_password();
		if ($data['numero'] == 'no') {
			$users = User::create([
				'name' => $data['name'],
				'surname' => $data['surname'],
				'email' => $data['email'],
				'dni' => $data['dni'],
				'roles_id' => $data['role'],
				'password' => Hash::make($data['password']),
			]);
			$urlPrimero = $_SERVER['REQUEST_URI'];
			//$urlPrimero = substr($urlPrimero,0,-13);
			$invitation = "/restablishpass/";
			$path = $urlPrimero . $invitation;
			$token = self::random_token();
			$url = $path . $token;
			$email = $data['email'];
			$archivo = fopen(storage_path(DIRECTORY_SEPARATOR."txt".DIRECTORY_SEPARATOR."cambioInicial_$email.txt"), "w");
			$dni = $data['dni'];
			$user = DB::table('users')->where('dni', $dni)->get();
			if ($archivo == false) {
				echo "Error al crear el archivo";
			} else {
				// Escribir en el archivo:
				fwrite($archivo, "Su usuario de SSM ha sido creado. El acceso será a través de su DNI\r\n\r\n");
				fwrite($archivo, "Correo: $email\r\n\r\n");
				fwrite($archivo, "Introduzca la contraseña para su usario a través del siguiente link\r\n");
				fwrite($archivo, "URL: $url\r\n");
				fwrite($archivo, "Las condiciones legales serán aceptadas en el momento que realiza el acceso.\r\n\r\n");
				fwrite($archivo, "Condiciones:\r\n\r\n");
				fwrite($archivo, "Lorem ipsum dolor sit amet consectetur adipiscing, elit sociis placerat suspendisse viverra mattis quam, et ultricies curabitur hac facilisis. Risus conubia maecenas platea nec justo tincidunt netus hac elementum odio, parturient nisl nibh cras aliquam sollicitudin mollis dictum curae venenatis curabitur, egestas facilisi ornare rutrum nulla aptent iaculis lectus metus. Et natoque placerat etiam vehicula varius ante tellus aptent bibendum, turpis convallis torquent tincidunt feugiat nam ridiculus scelerisque libero magna, suscipit iaculis sodales faucibus gravida a eleifend mus.
Fames congue nascetur erat montes a purus facilisi taciti, donec maecenas ultrices placerat gravida semper dignissim morbi, eget augue egestas bibendum posuere eleifend urna. Habitasse sociis ad torquent vivamus malesuada auctor class curae congue, tempor himenaeos tellus justo egestas lectus vehicula tincidunt, vel aliquet semper metus quisque libero nam id. Molestie vehicula netus pulvinar dapibus pretium platea justo tincidunt porttitor, donec ac vulputate vitae tortor leo aliquam nascetur sodales ante, per potenti tellus montes quam ad non nunc.\r\n");
				// Fuerza a que se escriban los datos pendientes en el buffer:
				fflush($archivo);
			}
			// Cerrar el archivo:
			fclose($archivo);
			$pdf = PDF::loadView('layouts.userFirstLogIn', compact('email', 'url', 'user'));
			$nombrePDF = "crearUsuario_" . $email;
			//dd($pdf);
			return view('layouts.userFirstLogIn', compact('email', 'url', 'user'));

			//return $pdf->stream($nombrePDF);
			//dd('aqui sigo');
			Session::flash('success', 'Usuario/s cargados correctamente');
			return $users;
		} else {

			if (is_uploaded_file($_FILES['file']['tmp_name'])) {

				$extension = File::extension($data['file']->getClientOriginalName());

				if ($extension == "xlsx" || $extension == "xls" || $extension == "csv") {

					$path = $data['file']->getRealPath();

					$items = Excel::load($path, function ($reader) {
					})->get();

					if (!empty($items) && $items->count()) {
						$i = 1;
						foreach ($items as $key => $value) {
							$email_exist = User::where('email', $value->email)->first();
							$dni_exist = User::where('dni', $value->dni)->first();
							$i++;
							$user = User::where('email', 'mateos.joaquin@gamaphone.com')->first();
							if (empty($value->nombre)) {
								Session::flash('error', 'Usuario/s no cargado/s. El nombre de la columna: ' . $i . ' está vacío.');
								return $user;
							}
							if (empty($value->apellidos)) {
								Session::flash('error', 'Usuario/s no cargado/s. Los apellidos de la columna: ' . $i . ' están vacíos.');
								return $user;
							}
							if (empty($value->email)) {
								Session::flash('error', 'Usuario/s no cargado/s. El email de la columna: ' . $i . ' está vacío.');
								return $user;
							}
							if (empty($value->dni)) {
								Session::flash('error', 'Usuario/s no cargado/s. El dni de la columna: ' . $i . ' está vacío.');
								return $user;
							}
							if (empty($value->rol)) {
								Session::flash('error', 'Usuario/s no cargado/s. El rol de la columna: ' . $i . ' está vacío.');
								return $user;
							}
							if ($email_exist) {
								Session::flash('error', 'Usuario/s no cargado/s. El e-mail: ' . $email_exist['email'] . ' ya existe.');
								return $email_exist;
							}
							if ($dni_exist) {
								Session::flash('error', 'Usuario/s no cargado/s. El dni: ' . $dni_exist['dni'] . ' ya existe.');
								return $dni_exist;
							}
						}
						foreach ($items as $key => $value) {
							$value->password = '1234';
							$users = User::create([
								'name' => $value->nombre,
								'surname' => $value->apellidos,
								'email' => $value->email,
								'dni' => $value->dni,
								'roles_id' => $value->rol,
								'password' => Hash::make($value->password),
							]);

						}

						Session::flash('success', 'Usuario/s cargados correctamente');

					}
					return $users;

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

	function random_token($length = 10)
	{
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$password = substr(str_shuffle($chars), 0, $length);
		return $password;
	}

}

