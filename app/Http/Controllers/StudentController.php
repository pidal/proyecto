<?php

namespace pfg\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use pfg\Models\Assignment;
use pfg\Models\GroupAssignment;
use pfg\Models\PruebasUnitarias;
use pfg\Models\RelUsersGroup;
use pfg\Models\RelUsersSubject;
use pfg\Models\StudentFile;
use pfg\Models\Subject;
use Session;
use Illuminate\Support\Facades\Storage;


class StudentController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
        $this->middleware('CheckStudent');
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
		$users = DB::table('users')->paginate(8);
		return view('layouts.usuariosInstructor', compact('users'));
	}

	public function showSubjects()
	{
        $subjects = RelUsersSubject::join('subject', 'subject_id', '=', 'subject.id')
            ->join('assignment', 'subject.id', 'assignment.subject_id')
            ->join('student_files', 'assignment.id', '=', 'student_files.assignment_id')
            ->where('rel_users_subject.users_id', auth()->id())
            ->where('student_files.users_id', auth()->id())
            ->select('subject.name', 'subject.id');

		$subjects = RelUsersGroup::join('group_assignment', 'rel_users_groups.group_assignment_id' ,'=', 'group_assignment.id')
			->join('assignment', 'assignment.id','=', 'group_assignment.assignment_id')
            ->join('subject', 'assignment.subject_id', '=', 'subject.id')
            ->where('rel_users_groups.users_id', auth()->id())
			->select('subject.name', 'subject.id')
            ->union($subjects)
            ->get();


		return view('layouts.showSubjectsStudent', compact('subjects'));
	}

	public function showResultsStudentSubjects()
	{
		$subjects = RelUsersSubject::join('subject', 'subject_id', '=', 'subject.id')
			->join('assignment', 'subject.id', 'assignment.subject_id')
			->join('student_files', 'assignment.id', '=', 'student_files.assignment_id')
			->where('rel_users_subject.users_id', auth()->id())
			->where('student_files.delivered',  1)
			->select('subject.name', 'subject.id')
            ->distinct('subject.id')
            ->paginate(1);

		return view('layouts.showResultsStudentSubjects', compact('subjects'));
	}

	public function showResultsAssignmentsStudent(Request $request)
	{

		$assignments = Assignment::select('assignment.*', 'group_assignment.id as group_assignment_id')
			->join('student_files', 'assignment.id', '=', 'student_files.assignment_id')
			->join('group_assignment', 'student_files.group_id', '=', 'group_assignment.id')
			->join('rel_users_groups', 'group_assignment.id', '=', 'rel_users_groups.group_assignment_id')
			->Where('rel_users_groups.users_id', auth()->id())
			->where('student_files.delivered', '=', 1)
			#->where('student_files.left_attempts', '>=', 1)
			->where('assignment.subject_id', $request->subject_id)
			->distinct('assignment.id');

		$assignments = Assignment::select('assignment.*', 'student_files.users_id as group_assignment_id')
			->join('student_files', 'assignment.id', '=', 'student_files.assignment_id')
			->where('student_files.users_id', auth()->id())
			->where('student_files.delivered', '=', 1)
			#->where('student_files.left_attempts', '>=', 1)
			->where('assignment.subject_id', $request->subject_id)
			->distinct('assignment.id')
            #->union($assignments)
			->paginate(1);

		$subject = Subject::find($request->subject_id);
		return view('layouts.showResultsAssignmentsStudent', compact('assignments', 'subject'));
	}

	public function showAssignmentsStudent(Request $request)
	{

		$assignments = Assignment::select('assignment.*', 'group_assignment.id as group_assignment_id')
			->join('student_files', 'assignment.id', '=', 'student_files.assignment_id')
			->join('group_assignment', 'student_files.group_id', '=', 'group_assignment.id')
			->join('rel_users_groups', 'group_assignment.id', '=', 'rel_users_groups.group_assignment_id')
			->where('student_files.left_attempts', '>=', 1)
			->Where('rel_users_groups.users_id', auth()->id())
			->where('assignment.subject_id', $request->subject_id)
			->distinct('assignment.id');

		$assignments = Assignment::select('assignment.*', 'student_files.group_id as group_assignment_id')
			->join('student_files', 'assignment.id', '=', 'student_files.assignment_id')
			->where('student_files.users_id', auth()->id())
			->where('student_files.left_attempts', '>=', 1)
			->where('assignment.subject_id', $request->subject_id)
			->distinct('assignment.id')
            ->union($assignments)
			->paginate(1);

		$subject = Subject::where('id', $request->subject_id)
            ->select('name','id')
            ->first();

		return view('layouts.showAssignmentsStudent', compact('assignments', 'subject'));
	}

	public function showStudentsFiles(Request $request)
	{
        $studentsUnion = Assignment::select('student_files.*')
            ->join('student_files', 'assignment.id', '=', 'student_files.assignment_id')
            ->join('group_assignment', 'student_files.group_id', '=', 'group_assignment.id')
            ->join('rel_users_groups', 'group_assignment.id', '=', 'rel_users_groups.group_assignment_id')
            ->Where('rel_users_groups.users_id', auth()->id())
            ->where('assignment.id', $request->assignment_id)
            ->where('student_files.users_id', auth()->id());

        $studentsFiles = Assignment::select('student_files.*')
            ->join('student_files', 'assignment.id', '=', 'student_files.assignment_id')
            ->where('assignment.id', $request->assignment_id)
            ->where('student_files.users_id', auth()->id())
            ->union($studentsUnion)
            ->get();

            //dd($studentsFiles);


		$assignment = Assignment::where('id', $request->assignment_id)->first();
		$subject = Subject::where('id', $request->subject_id)->first();

		return view('layouts.showStudentsFiles', compact('assignment', 'studentsFiles', 'subject'));
	}


	public function selectedSubject(Request $request, $subject)
	{
		$subjects = DB::table('rel_users_subject')->join('subject', 'subject_id', '=', 'subject.id')->where('rel_users_subject.users_id', auth()->id())->pluck('subject.name', 'subject.id');
		return view('layouts.showSubjectsStudent', compact('subjects'));
	}


	public function sendStudentFiles(Request $request)
	{

        $studentsUnion = StudentFile::select('student_files.*')
            ->join('assignment', 'assignment.id', '=', 'student_files.assignment_id')
            ->join('group_assignment', 'student_files.group_id', '=', 'group_assignment.id')
            ->join('rel_users_groups', 'group_assignment.id', '=', 'rel_users_groups.group_assignment_id')
            ->Where('rel_users_groups.users_id', auth()->id())
            ->where('assignment.id', $request->assignment_id)
            ->where('student_files.users_id', auth()->id());

        $studentsFiles = StudentFile::select('student_files.*')
            ->join('assignment', 'assignment.id', '=', 'student_files.assignment_id')
            ->where('assignment.id', $request->assignment_id)
            ->where('student_files.users_id', auth()->id())
            ->union($studentsUnion)
            ->get();


        $errors = array();
        foreach($studentsFiles as $studentsFile){
            $file = $request->file('file'.$studentsFile->id);
            if($file == null){
                $errors['file'.$studentsFile->id] = 'El archivo es requerido';
            }
            if($file != null && $file->getClientOriginalName() != $studentsFile->fileName){
                $errors['file'.$studentsFile->id] = 'El nombre del archivo no corresponde al archivo solicitado.';
            }
        }
        if(count($errors) > 0){
            return redirect('showStudentsFiles/subject/'.$request->subject_id.'/assignment/'.$request->assignment_id)->with('errors', $errors);
        }

        foreach($studentsFiles as $studentFile){

            $record = Assignment::join('student_files', 'assignment.id','=', 'student_files.assignment_id')->where('student_files.id', $studentFile->id)->first();
			$fileInstructor = $record->correction_file;
			$language = $record->language;

			$path_save = $studentFile->id . '_' . $studentFile->left_attempts;
			$studentFile->delivered = 1;
            $file = $request->file('file'.$studentFile->id);
			Storage::put($path_save . DIRECTORY_SEPARATOR . $file->getClientOriginalName(), file_get_contents($file));

			$pruebasUnitarias = new PruebasUnitarias();

			if ($language == 'c') {
                $studentFile = $pruebasUnitarias->executeLanguageC($fileInstructor,$studentFile);
			}
			elseif ($language == 'java') {
                $studentFile = $pruebasUnitarias->executeLanguageJava($fileInstructor,$studentFile);
			}
			elseif ($language == 'c#') {
                $studentFile = $pruebasUnitarias->executeLanguageCsharp($fileInstructor,$studentFile);
			}

			$studentFile->left_attempts = $studentFile->left_attempts - 1;
            $studentFile->delivered = 1;
			$path = storage_path(DIRECTORY_SEPARATOR.'TODO'.DIRECTORY_SEPARATOR . $studentFile->id . '_' . $studentFile->left_attempts);
            if (!file_exists($path)) {
                mkdir($path);
            }

			chdir($path);
			$newPath = $studentFile->id . '_' . $studentFile->left_attempts;
			Storage::move($path_save . DIRECTORY_SEPARATOR . $fileInstructor, $newPath . DIRECTORY_SEPARATOR . $fileInstructor);
			$studentFile->save();

		}

		Session::flash('success', 'Práctica entregada correctamente');
		return redirect()->route('showResultsStudent',array('subject_id' => $request->subject_id, 'assignment_id' => $request->assignment_id));
	}

	public function showResultsStudent(Request $request)
	{

        $studentsFiles = Assignment::select('student_files.*')
            ->join('student_files', 'assignment.id', '=', 'student_files.assignment_id')
            ->where('assignment.id', $request->assignment_id)
            ->where('student_files.users_id', auth()->id())
            ->paginate(1);

		$assignment = Assignment::find($request->assignment_id);;
		$subject = Subject::find($request->subject_id);
		return view('layouts.showResultsStudent', compact('studentsFiles', 'subject', 'assignment'));
	}


	/**
	 * Show the step 1 Form for creating a new product.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function createAssignmentStep1(Request $request)
	{

		$assignment = $request->session()->get('assignment');
		$student = $request->session()->get('student');
		// dd($student);
		$subjects = DB::table('rel_users_subject')->join('subject', 'subject_id', '=', 'subject.id')->where('rel_users_subject.users_id', auth()->id())->pluck('subject.name', 'subject.id');
		//dd($assignment);
		return view('layouts.createAssignment-Step1', compact('assignment', $assignment, 'subjects', 'student', $student));

	}


	/**
	 * Post Request to store step1 info in session
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function postCreateAssignmentStep1(Request $request)
	{


		$validatedData = $request->validate([
			'name' => 'required',
			'number_files_delivered' => 'required',
			'attempts' => 'required',
			'subject_id' => 'required',
			'language' => 'required',
			'call' => 'required',
		]);

		$assignment = new Assignment();
		$assignment->fill($validatedData);
		$request->session()->put('assignment', $assignment);
		$total = 0;
		//dd($request);
		for ($i = 1; $i <= $assignment['number_files_delivered']; $i++) {
			$student [$i] = new StudentFile();
			$student [$i] ['fileName'] = $request['fileName_' . $i];
			$student [$i] ['weight'] = $request['weight_' . $i];
			$total = $total + $request['weight_' . $i];

			if ($student [$i] ['fileName'] == null) {
				Session::flash('error', 'El nombre del fichero ' . $i . ' está vacío');
				return redirect('/createAssignment-Step1');
			}
			if ($student [$i] ['weight'] == null) {
				Session::flash('error', 'La ponderación del fichero ' . $i . ' está vacío');
				return redirect('/createAssignment-Step1');
			}

		}
		if ($total != 100) {
			Session::flash('error', 'La ponderación del fichero debe sumar 100% en total el valor actual es de ' . $total . '%');
			return redirect('/createAssignment-Step1');
		}
		$request->session()->put('student', $student);


		return redirect('/createAssignment-Step2');

	}

	/**
	 * Show the step 2 Form for creating a new product.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function createAssignmentStep2(Request $request)
	{

		$student = $request->session()->get('student');
		$assignment = $request->session()->get('assignment');
		$groupAssignment = $request->session()->get('groupAssignment');
		$groupAssignment = $groupAssignment[1];
		$relUsersGroup = $request->session()->get('relUsersGroup');
		$users = DB::table('users')->join('rel_users_subject', 'users.id', '=', 'rel_users_subject.users_id')->where('users.roles_id', '3')->where('rel_users_subject.subject_id', $assignment['subject_id'])->get();
		//dd($users);
		return view('layouts.createAssignment-Step2', compact('assignment', $assignment, 'groupAssignment', $groupAssignment, 'relUsersGroup', $relUsersGroup, 'student', $student, 'users'));
	}

	/**
	 * Post Request to store step1 info in session
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function postCreateAssignmentStep2(Request $request)
	{
		$student = $request->session()->get('student');
		$assignment = $request->session()->get('assignment');
		$groupAssignment = $request->session()->get('groupAssignment');
		$relUsersGroup = $request->session()->get('relUsersGroup');


		$validatedData = $request->validate([
			'type' => 'required',
		]);
		$assignment->fill($validatedData);
		$request->session()->put('assignment', $assignment);
		if (empty($request->session()->get('groupAssignment'))) {
			if ($request['type'] == 'grupo') {
				$request->validate([
					'members_number' => 'required',
				]);
				//TOTAL DE ALUMNOS DE LA ASIGNATURA DE LA PRÁCTICA / TOTAL MIEMBROS POR GRUPO
				$total = ceil($request['alumnos'] / $request['members_number']);

				$k = 0;
				for ($i = 1; $i <= $total; $i++) {
					$groupAssignment [$i] = new GroupAssignment();
					$groupAssignment [$i] ['name'] = 'Grupo: ' . $i;
					$groupAssignment [$i] ['members_number'] = $request['members_number'];
					for ($j = 1; $j <= $request['members_number']; $j++) {
						$k++;
						if ($k <= $request['alumnos']) {
							$relUsersGroup[$i] [$j] = new RelUsersGroup();
							$relUsersGroup[$i] [$j] ['users_id'] = $request['users_id_' . $i . '_' . $j];
							$infoOK = $request['users_id_' . $i . '_' . $j];
							if ($infoOK == null) {
								Session::flash('error', 'El nombre del alumno ' . $j . ' del grupo ' . $i . ' está vacío');
								return redirect('/createAssignment-Step2');
							}
						}
					}
				}
				$request->session()->put('groupAssignment', $groupAssignment);
				$request->session()->put('relUsersGroup', $relUsersGroup);

			} else {
				$users = DB::table('users')->join('rel_users_subject', 'users.id', '=', 'rel_users_subject.users_id')->where('users.roles_id', '3')->where('rel_users_subject.subject_id', $assignment['subject_id'])->get();
				$request->session()->put('users', $users);
			}
		}

		return redirect('/createAssignment-Step3');
	}

	public function createAssignmentStep3(Request $request)
	{
		$student = $request->session()->get('student');
		$assignment = $request->session()->get('assignment');
		$groupAssignment = $request->session()->get('groupAssignment');
		$relUsersGroup = $request->session()->get('relUsersGroup');
		$users = $request->session()->get('users');


		return view('layouts.createAssignment-Step3', compact('assignment', $assignment, 'groupAssignment', $groupAssignment, 'relUsersGroup', $relUsersGroup, 'student', $student));

	}

	/**
	 * Store product
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function postCreateAssignmentStep3(Request $request)
	{


		$student = $request->session()->get('student');
		$assignment = $request->session()->get('assignment');
		$groupAssignment = $request->session()->get('groupAssignment');
		$relUsersGroup = $request->session()->get('relUsersGroup');
		$users = $request->session()->get('users');


		$request->validate([
			'file' => 'required',
			'delivered_date' => 'required'
		]);


		$assignment = $request->session()->get('assignment');

		$assignmentSave = new Assignment();
		$assignmentSave->name = $assignment['name'];
		$assignmentSave->delivered_date = $request['delivered_date'];
		$assignmentSave->correction_file = $request['file']->getClientOriginalName();;
		$assignmentSave->number_files_delivered = $assignment['number_files_delivered'];
		$assignmentSave->attempts = $assignment['attempts'];
		$assignmentSave->call = $assignment['call'];
		$assignmentSave->language = $assignment['language'];
		$assignmentSave->type = $assignment['type'];
		$assignmentSave->subject_id = $assignment['subject_id'];
		$assignmentSave->created_by = auth()->id();
		$assignmentSave->save();


		$studentsFile = $request->session()->get('student');
		$relUsersGroup = $request->session()->get('relUsersGroup');
		if ($assignmentSave->type == 'grupo') {
			$groupAssignment = $request->session()->get('groupAssignment');
			foreach ($groupAssignment as $group) {
				$groupAssignmentSave = new GroupAssignment();
				$groupAssignmentSave->name = $group['name'];
				$groupAssignmentSave->members_number = $group['members_number'];
				$groupAssignmentSave->assignment_id = $assignmentSave->id;
				$groupAssignmentSave->save();
				$groups_ids [] = $groupAssignmentSave->id;
				foreach ($studentsFile as $student) {
					$studentFileSave = new StudentFile();
					$studentFileSave->fileName = $student['fileName'];
					$studentFileSave->weight = $student['weight'];
					$studentFileSave->assignment_id = $assignmentSave->id;
					$studentFileSave->left_attempts = $assignmentSave->attempts;
					$studentFileSave->group_id = $groupAssignmentSave->id;
					$studentFileSave->save();
				}
			}
			$i = 0;
			foreach ($relUsersGroup as $relUserGroup) {
				foreach ($relUserGroup as $rel) {
					$relUsersGroupSave = new RelUsersGroup();
					$relUsersGroupSave->users_id = $rel['users_id'];
					$relUsersGroupSave->group_assignment_id = $groups_ids[$i];
					$relUsersGroupSave->save();
				}
				$i++;
			}
		} else {
			$users = $request->session()->get('users');
			foreach ($users as $user) {
				foreach ($studentsFile as $student) {
					$studentFileSave = new StudentFile();
					$studentFileSave->fileName = $student['fileName'];
					$studentFileSave->weight = $student['weight'];
					$studentFileSave->assignment_id = $assignmentSave->id;
					$studentFileSave->left_attempts = $assignmentSave->attempts;
					$studentFileSave->users_id = $user->id;
					$studentFileSave->save();
				}
			}
		}

		$request->session()->forget(['student', 'assignment', 'groupAssignment', 'relUsersGroup']);
		Session::flash('success', 'Práctica creada correctamente');
		return redirect('/createAssignment-Step1');
	}

}
