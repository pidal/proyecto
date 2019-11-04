<?php

namespace pfg\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use pfg\Models\Assignment;
use pfg\Models\GroupAssignment;
use pfg\Models\Practica;
use Illuminate\Support\Facades\DB;
use pfg\Models\RelUsersGroup;
use pfg\Models\StudentFile;
use Session;
use PDF;

class TeacherAssigmentController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function __construct()
	{
		$this->middleware('auth');
        $this->middleware('CheckTeacher');
	}

    public function index(Request $request)
    {
        $assigments = DB::table('assignment')
            ->where('assignment.created_by', auth()->id(),'assignment.delivered_date >= now()')
            ->join('subject','subject.id', '=','assignment.subject_id')
            ->select('assignment.*','subject.name as subject')
			->orderBy('id', 'DESC');

        if (isset($_GET['subject'])) {
            $assigments->where('assignment.subject_id', $_GET['subject']);
        }

        $assigments = $assigments->paginate(8);

        $subjects = DB::table('subject')->get();
        return view('TeacherAssigment.index', compact('assigments', 'subjects'));
    }

    public function add(Request $request){
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
        return view('TeacherAssigment.create.createAssignment', compact('subjects', 'student', 'users'));
    }

    public function edit(Request $request, int $id){

		$assignment = Assignment::find($id);

		$files = StudentFile::groupBy('fileName')->where('assignment_id', $assignment->id)->get();

		dd($files);

        $subjects = DB::table('rel_users_subject')
            ->join('subject', 'rel_users_subject.subject_id', '=', 'subject.id')
            ->where('rel_users_subject.users_id', auth()->id())
            ->pluck('subject.name', 'subject.id');

        $users = DB::table('users')
            ->join('rel_users_subject', 'users.id', '=', 'rel_users_subject.users_id')
			->select('users.id', 'users.name')
            ->where('users.roles_id', '3')
            ->where('rel_users_subject.subject_id', $assignment->subject_id)
            ->get();

		$query = DB::table('users')
			->join('rel_users_subject', 'users.id', '=', 'rel_users_subject.users_id')
			->select('users.id', 'users.name')
			->where('rel_users_subject.subject_id', $assignment->subject_id)
			->get();
		$student = array('number_students' => count($query), 'users' => $query);

		$groups = GroupAssignment::where('assignment_id',$assignment->id)->get();
		$group_assignment = array();
		foreach($groups as $group)
		{
			$relUserGroup = RelUsersGroup::where('group_assignment_id', $group->id)->get();
			$relUser = new \StdClass();
			$relUser->groupName = $group->name;
			$relUser->groupId = $group->id;
			$relUser->members_number = $group->members_number;
			$relUser->students = $relUserGroup;
			$group_assignment[] = $relUser;
		}

        return view('TeacherAssigment.edit.createAssignment',
			compact('subjects', 'student', 'users', 'assignment', 'group_assignment')
		);
    }

    public function destroy($id){
	    Assignment::find($id)->delete();
        return redirect()->route('teacherassignment.index')->with('success', 'Registro eliminado satisfactoriamente');
	}

	public function create(Request $request)
	{
		Session::flash('redirect', 'teacherassignmentadd');

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
				return redirect('/teacherassignmentadd')->withInput();
			}
			if ( $request->{'weight_' . $i} == null) {
				Session::flash('error', 'La ponderación del fichero ' . $i . ' está vacío');
				return redirect('/teacherassignmentadd')->withInput();
			}

		}
		if ($total != 100) {
			Session::flash('error', 'La ponderación del fichero debe sumar 100% en total el valor actual es de ' . $total . '%');
			return redirect('/teacherassignmentadd')->withInput();
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

				if($k>=count($alumnos)) break;
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
					if( !is_dir($path) ) mkdir($path);

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
							return redirect('/teacherassignmentadd')->withInput();
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

					//echo $path."<br>";

					$path2 = DIRECTORY_SEPARATOR . $studentFileSave->id.'_'.$studentFileSave->left_attempts;
					Storage::put($path2 .DIRECTORY_SEPARATOR. $file->getClientOriginalName() , file_get_contents($file));

					//echo $path2;
				}
			}

		}

		Session::flash('success', 'Práctica creada correctamente');
		return redirect('/teacherassignment');
	}

	public function saveedit(Request $request){

		$validatedData = $request->validate([
			'id' => 'required',
			'name' => 'required',
			'number_files_delivered' => 'required',
			'attempts' => 'required',
			'subject_id' => 'required',
			'language' => 'required',
			'call' => 'required',
			'type' => 'required',
			'delivered_date' => 'required'
		]);

		$assignment = Assignment::find($request->id);
		$assignment->fill($validatedData);
		$assignment->save();

		Session::flash('success', 'Práctica editada correctamente');
		return redirect('/teacherassignment');
	}

}
