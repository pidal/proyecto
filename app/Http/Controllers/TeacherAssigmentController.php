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
            ->select('assignment.*','subject.name as subject');

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

    public function edit(Request $request){
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

    public function destroy(Request $request){}

}
