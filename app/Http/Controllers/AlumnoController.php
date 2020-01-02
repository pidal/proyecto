<?php

namespace pfg\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use pfg\Http\Requests\StoreArchivosRequest;
use pfg\Models\Archivo;
use pfg\Models\ArchivosHistorico;
use pfg\Models\ArchivosFallado;
use pfg\Models\ArchivosHistoricosFallado;
use pfg\Models\Asignatura;
use pfg\Models\Practica;
use Illuminate\Support\Facades\Storage;
use pfg\Models\User;
use Illuminate\Support\Facades\Redirect;

class AlumnoController extends Controller
{
	/**1
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$idRol = DB::table('profesor')->where('users_id', auth()->id())->value('roles_id');
		if ($idRol == null) {
			$idRol = DB::table('alumno')->where('users_id', auth()->id())->value('roles_id');
		}

		if ($idRol == 2) {

			/*
			 * Asignaturas con prácticas y archivos disponibles
			 */
			$asignaturas = Asignatura::select('asignatura.id', 'asignatura.name')
				->join('profesor', 'asignatura.profesor_id', 'profesor.id')
				->join('practicas', 'asignatura.id', 'practicas.asignatura_id')
				->join('archivos', 'practicas.id', 'archivos.practicas_id')
				->where('practicas.asignatura_id', '<>', 'null')
				->where('archivos.practicas_id', '<>', 'null')
				->where('profesor.users_id', '=', auth()->id())
				->distinct()
				->get();
			return view('alumno.asignatura', compact('asignaturas'));
		} else {
			$asignaturas = Asignatura::select('asignatura.id', 'asignatura.name')
				->join('alumno', 'asignatura.alumno_id', 'alumno.id')
				->join('practicas', 'asignatura.id', 'practicas.asignatura_id')
				->join('archivos', 'practicas.id', 'archivos.practicas_id')
				->where('practicas.asignatura_id', '<>', 'null')
				->where('archivos.practicas_id', '<>', 'null')
				->where('archivos.intentos', '<>', '0')
				->where('alumno.users_id', '=', auth()->id())
				->distinct()
				->get();
			return view('alumno.asignatura', compact('asignaturas'));
		}

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
	public function store(Request $request)
	{

		$file_prof = Practica::select('practicas.file_prof')
			->join('archivos', 'practicas.id', 'archivos.practicas_id')
			->where('archivos.id', $request->id)->get();
		$file_prof = $file_prof[0]->file_prof;
		$path = storage_path(DIRECTORY_SEPARATOR.'TODO');
		chdir($path);
		// dd(shell_exec('pwd'));


		$file = $request->file('file');
		$file_name = $file->getClientOriginalName();
		$codif = "file --mime-encoding -b " . $file->getClientOriginalName() . "|tr -d '\n'";
		$codif = shell_exec($codif);
		$encode = " iconv -f $codif -t utf8 $file_name > $file_name" . "2";
		shell_exec($encode);
		$mover = "mv $file_name" . "2" . " $file_name";
		shell_exec($mover);
		$eliminar = "rm $file_name" . "2";
		shell_exec($eliminar);


		//válido para extensiones de c y txt
		if ($file->extension() == 'c' || 'txt') {

			$archivo = Archivo::find(request('id'));
			$archivo->file_name = $request->file('file')->getClientOriginalName();
			$archivo->save();


			if ($archivo->total != null) {
				$archivo_historico = new ArchivosHistorico();
				$archivo_historico->total = $archivo->total;
				$archivo_historico->pasados = $archivo->pasados;
				$archivo_historico->fallados = $archivo->fallados;
				$archivo_historico->nota = $archivo->nota;
				$archivo_historico->intentos = $archivo->intentos;
				$archivo_historico->updated_at = $archivo->updated_at;
				$archivo_historico->archivos_id = $archivo->id;
				$archivo_historico->save();
			}

			/*
			 * Creamos un directorio con el nombre de la practica y la fecha de expiración
			 */

			$path = $request->name . '_' . $request->intentos;
			mkdir($path);
			chdir($path);


			/* Indicamos que queremos guardar un nuevo archivo en el disco local.
			En este caso la ruta será: '/Users/joaquin/Documents/PFG/mi-proyecto-laravel/storage'
			file_get_contents: Guardar el archivo directamente, sin crear directorios.
			*/
			$number = $request->intentos + 1;
			$path2 = $request->name . '_' . $number;
			Storage::disk('local')->put($archivo->file_name, file_get_contents($file));

			$current_path = storage_path(DIRECTORY_SEPARATOR.'TODO'.DIRECTORY_SEPARATOR . $archivo->file_name);
			$new_path = storage_path(DIRECTORY_SEPARATOR.'TODO'.DIRECTORY_SEPARATOR. $path . DIRECTORY_SEPARATOR . $archivo->file_name);
			rename($current_path, $new_path);

			$current_path = storage_path(DIRECTORY_SEPARATOR.'TODO'.DIRECTORY_SEPARATOR . $path2 . DIRECTORY_SEPARATOR . $file_prof);
			$new_path = storage_path(DIRECTORY_SEPARATOR.'TODO'.DIRECTORY_SEPARATOR . $path . DIRECTORY_SEPARATOR . $file_prof);
			rename($current_path, $new_path);

			//Compilamos el test a realizar
			$exec = 'gcc ' . $file_prof . ' -I/lib/include -lcunit  -o ' . $archivo->file_name;

			shell_exec($exec);
			//Ejecutamos el archivo creado de la compilación
			$ejecutable = './' . $archivo->file_name;
			$salida = shell_exec($ejecutable);

			$archivo->intentos = $archivo->intentos - 1;
			$archivo->save();

			$archivo = Archivo::find(request('id'));
			$weight = Archivo::select('weight')
				->where('id', $request->id)->get();
			$xml = simplexml_load_file('CUnitAutomated-Results.xml');
			$archivo->total = $xml->CUNIT_RUN_SUMMARY->CUNIT_RUN_SUMMARY_RECORD[2]->TOTAL;
			$archivo->pasados = $xml->CUNIT_RUN_SUMMARY->CUNIT_RUN_SUMMARY_RECORD[2]->SUCCEEDED;
			$archivo->fallados = $xml->CUNIT_RUN_SUMMARY->CUNIT_RUN_SUMMARY_RECORD[2]->FAILED;
			$archivo->nota = ($archivo->pasados / $archivo->total) * $weight[0]->weight * 10;
			$archivo->save();


			$archivos_fallado = ArchivosFallado::select('archivos_fallados.descripcion')
				->join('archivos', 'archivos_fallados.archivos_id', 'archivos.id')
				->where('archivos.id', $request->id)->get();
			ArchivosFallado::select('archivos_fallados.id')
				->join('archivos', 'archivos_fallados.archivos_id', 'archivos.id')
				->where('archivos.id', $request->id)->delete();
			$archivos_historicos = ArchivosHistorico::select('archivos_historicos.id')
				->where('archivos_historicos.archivos_id', $request->id)
				->max('archivos_historicos.id');


			foreach ($archivos_fallado as $archivo_fallado) {
				$archivo_historico_fallado = new ArchivosHistoricosFallado();
				$archivo_historico_fallado->descripcion = $archivo_fallado->descripcion;
				$archivo_historico_fallado->archivos_historicos_id = $archivos_historicos;
				$archivo_historico_fallado->save();
			}
			if ($archivo->fallados != 0) {

				foreach ($xml->CUNIT_RESULT_LISTING->CUNIT_RUN_SUITE->CUNIT_RUN_SUITE_SUCCESS->CUNIT_RUN_TEST_RECORD as $nodo) {
					$archivos_fallados = new ArchivosFallado();
					$archivos_fallados->archivos_id = $archivo->id;
					$archivos_fallados->descripcion = $nodo->CUNIT_RUN_TEST_FAILURE->CONDITION;
					$archivos_fallados->save();
					$failresults[] = $nodo->CUNIT_RUN_TEST_FAILURE->CONDITION;
				}
			}


			$archivos[] = $archivo;
			$notafinal = -1;

			$old_name = storage_path(DIRECTORY_SEPARATOR.'TODO'.DIRECTORY_SEPARATOR . $path2 . DIRECTORY_SEPARATOR . $archivo->file_name);
			$old_xml = storage_path(DIRECTORY_SEPARATOR.'TODO'.DIRECTORY_SEPARATOR . $path2 . DIRECTORY_SEPARATOR . 'CUnitAutomated-Results.xml');
			$old_path = storage_path(DIRECTORY_SEPARATOR.'TODO'.DIRECTORY_SEPARATOR . $path2);

			unlink($old_name);
			unlink($old_xml);
			rmdir($old_path);

			return view('alumno.resultados', compact('archivos', 'failresults', 'notafinal'));
			// return  dd($salida);
		} else {
			return "extensión no compatible";
		}

	}

	/**
	 * Display the specified resource.
	 *
	 * @param int $id
	 * @return \Illuminate\Http\Response
	 */
	public function show()
	{
		$idRol = DB::table('profesor')->where('users_id', auth()->id())->value('roles_id');
		if ($idRol == null) {
			$idRol = DB::table('alumno')->where('users_id', auth()->id())->value('roles_id');
		}
		//   $idRol =  DB::table('users')->where('id',auth()->id())->value('role');

		if ($idRol == 2) {
			/*
			 * Asignaturas con prácticas y archivos disponibles
			 */
			$asignaturas = Asignatura::select('asignatura.id', 'asignatura.name')
				->join('profesor', 'asignatura.profesor_id', 'profesor.id')
				->join('practicas', 'asignatura.id', 'practicas.asignatura_id')
				->join('archivos', 'practicas.id', 'archivos.practicas_id')
				->where('practicas.asignatura_id', '<>', 'null')
				->where('archivos.practicas_id', '<>', 'null')
				->where('profesor.users_id', '=', auth()->id())
				->distinct()
				->get();
			return view('alumno.asignatura', compact('asignaturas'));
		} else {
			$asignaturas = Asignatura::select('asignatura.id', 'asignatura.name')
				->join('alumno', 'asignatura.alumno_id', 'alumno.id')
				->join('practicas', 'asignatura.id', 'practicas.asignatura_id')
				->join('archivos', 'practicas.id', 'archivos.practicas_id')
				->where('practicas.asignatura_id', '<>', 'null')
				->where('archivos.practicas_id', '<>', 'null')
				->where('archivos.intentos', '<>', '0')
				->where('alumno.users_id', '=', auth()->id())
				->distinct()
				->get();
			return view('alumno.mostrar', compact('asignaturas'));
		}
	}

	public function practicas(Request $request)
	{
		$idRol = DB::table('profesor')->where('users_id', auth()->id())->value('roles_id');
		if ($idRol == null) {
			$idRol = DB::table('alumno')->where('users_id', auth()->id())->value('roles_id');
		}
		if ($idRol == 2) {
			$practicas = Practica::select('practicas.*', 'practicas.name')
				->where('asignatura_id', '=', $request->asignatura)
				->join('archivos', 'practicas.id', 'archivos.practicas_id')
				->join('asignatura', 'practicas.asignatura_id', 'asignatura.id')
				->join('profesor', 'asignatura.profesor_id', 'profesor.id')
				->where('profesor.users_id', '=', auth()->id())
				->where('archivos.intentos', '>=', 0)
				->distinct()
				->get();
			return view('alumno.index', compact('archivos'));

		} elseif ($idRol == 3) {

			$practicas = Practica::select('practicas.*', 'practicas.name', 'asignatura.name as asname')
				->where('asignatura_id', '=', $request->asignatura)
				->join('archivos', 'practicas.id', 'archivos.practicas_id')
				->join('asignatura', 'practicas.asignatura_id', 'asignatura.id')
				->join('alumno', 'asignatura.alumno_id', 'alumno.id')
				->where('alumno.users_id', '=', auth()->id())
				->where('archivos.intentos', '>=', 0)
				->distinct()
				->get();

			$practicas[0]->vista = $request->vista;
			return view('alumno.practicas', compact('practicas'));
		}
	}

	public function archivos(Request $request)
	{

		if ($request->vista == "resultado") {

			$idRol = DB::table('profesor')->where('users_id', auth()->id())->value('roles_id');
			if ($idRol == null) {
				$idRol = DB::table('alumno')->where('users_id', auth()->id())->value('roles_id');
			}
			if ($idRol == 2) {
				$archivos = Archivo::where('expired_date', '>=', date('Y-m-d G:i:s'))
					->join('practicas', 'archivos.practicas_id', 'practicas.id')
					->join('asignatura', 'practicas.asignatura_id', 'asignatura.id')
					->join('profesor', 'asignatura.profesor_id', 'profesor.id')
					->where('profesor.users_id', '=', auth()->id())
					->where('archivos.practicas_id', $request->practica)
					->get();
				return view('alumno.index', compact('archivos'));

			} elseif ($idRol == 3) {

				$archivos = Archivo::select('archivos.*', 'practicas.file_prof', 'practicas.name as pname')
					->join('practicas', 'archivos.practicas_id', 'practicas.id')
					->join('asignatura', 'practicas.asignatura_id', 'asignatura.id')
					->join('alumno', 'asignatura.alumno_id', 'alumno.id')
					->where('alumno.users_id', '=', auth()->id())
					->where('archivos.practicas_id', $request->practica)
					->get();
				foreach ($archivos as $archivo) {
					$notas[] = $archivo->nota;
					$notafinal = 0;
					foreach ($notas as $nota) {
						$notafinal = $nota + $notafinal;
					}
					$archivos_fallados = ArchivosFallado::select('idarchivos_fallados', 'descripcion')
						->where('archivos_id', '=', $archivo->id)
						->get();
					foreach ($archivos_fallados as $fallado) {
						$failresults[] = $fallado->descripcion;
					}
				}
				$pname = $archivos[0]->pname;

				return view('alumno.resultados', compact('archivos', 'failresults', 'notafinal', 'pname'));
			}

		} else {
			$idRol = DB::table('profesor')->where('users_id', auth()->id())->value('roles_id');
			if ($idRol == null) {
				$idRol = DB::table('alumno')->where('users_id', auth()->id())->value('roles_id');
			}
			if ($idRol == 2) {
				$archivos = Archivo::where('expired_date', '>=', date('Y-m-d G:i:s'))
					->where('intentos', '>', 0)
					->join('practicas', 'archivos.practicas_id', 'practicas.id')
					->join('asignatura', 'practicas.asignatura_id', 'asignatura.id')
					->join('profesor', 'asignatura.profesor_id', 'profesor.id')
					->where('profesor.users_id', '=', auth()->id())
					->where('archivos.practicas_id', $request->practica)
					->get();
				return view('alumno.index', compact('archivos'));

			} elseif ($idRol == 3) {

				$archivos = Archivo::select('archivos.*', 'practicas.file_prof')
					->where('expired_date', '>=', date('Y-m-d G:i:s'))
					->where('intentos', '>', 0)
					->join('practicas', 'archivos.practicas_id', 'practicas.id')
					->join('asignatura', 'practicas.asignatura_id', 'asignatura.id')
					->join('alumno', 'asignatura.alumno_id', 'alumno.id')
					->where('alumno.users_id', '=', auth()->id())
					->where('archivos.practicas_id', $request->practica)
					->get();

				return view('alumno.index', compact('archivos'));
			}
		}

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
}
