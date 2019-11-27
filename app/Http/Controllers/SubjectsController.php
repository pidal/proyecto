<?php

namespace pfg\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use pfg\Models\Subject;
use pfg\Models\User;
use pfg\Models\RelUsersSubject;
use Session;
use Auth;


class SubjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $my_subjects = Subject::whereHas('users', function($q){
            $q->where('users.id', Auth::user()->id);
        })->pluck('id');
        $my_subjects = ($my_subjects->isEmpty()) ? collect([0]) : $my_subjects;

        $subjects = Subject::whereIn('id', $my_subjects)->orderBy('id', 'DESC')->paginate(7);

        return view('subjects.index', compact('subjects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('subjects.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Session::flash('redirect', 'subjects/create');

        $messages = [
            'name.required' => 'El campo nombre es obligatorio.',
            'grade.required' => 'El campo grado es obligatorio.',
            'description.required' => 'El campo descripción es obligatorio.',
        ];

        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
            'grade' => 'required',
            'imagen' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:1024'
        ], $messages);



        $subject = Subject::create($request->all());

        if($request->hasFile('imagen')){
            $imagen = $request->file('imagen');
            $imagenName = $subject->id . '_' . time() . '.' . $imagen->getClientOriginalExtension();

            Storage::disk('public')->put('/image/subjects/'.$imagenName, file_get_contents($imagen), 'public');

            $subject->imagen = $imagenName;
            $subject->save();
        }

        return redirect()->route('subjects.index')->with('success', 'Registro creado satisfactoriamente');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $subject = Subject::find($id);
        return view('subjects.show', compact('subject'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $subject = Subject::find($id);
        return view('subjects.edit', compact('subject'));
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
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
            'grade' => 'required',
            'imagen' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:1024'
        ]);
        $subject = Subject::find($id)->update($request->all());
        if($request->hasFile('imagen')){
            $imagen = $request->file('imagen');
            $imagenName = $subject->id . '_' . time() . '.' . $imagen->getClientOriginalExtension();

            Storage::disk('public')->put('/image/subjects/'.$imagenName, file_get_contents($imagen), 'public');

            $subject->imagen = $imagenName;
            $subject->save();
        }
        return redirect()->route('subjects.index')->with('success', 'Registro Actualizado satisfactoriamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Subject::find($id)->delete();
        return redirect()->route('subjects.index')->with('success', 'Registro eliminado satisfactoriamente');
    }

    public function relateSubjects($subject_id)
    {
        $subject = Subject::find($subject_id);

        $users = RelUsersSubject::join('users', 'users.id', '=', 'rel_users_subject.users_id')
            ->select('rel_users_subject.id', 'rel_users_subject.users_id', 'users.name', 'users.email')
            ->where('rel_users_subject.subject_id', $subject_id)
            ->orderBy('users.id', 'DESC')
            ->paginate(7);

        $allUsers = User::leftJoin('rel_users_subject', function($join) use($subject_id){
                    $join->on('users.id','=','rel_users_subject.users_id')
                        ->where('rel_users_subject.subject_id',$subject_id);
                })
                ->select('users.id','users.name')
                ->where('rel_users_subject.subject_id',null)
                ->where('users.roles_id','!=', 1)
                ->distinct('users.id')
                ->get();

        return view('subjects.relateSubjects', compact('subject', 'users', 'subject_id','allUsers'));
    }

    public function postrelateSubjects(Request $request)
    {
        $this->validate($request, [
            'users_id' => 'required',
            'subject_id' => 'required'
        ]);
        RelUsersSubject::create($request->all());
        return redirect()->route('relateSubjects',$request->subject_id)->with('success', 'Registro insertado satisfactoriamente');
    }

    public function relatedUserdestroy(Request $request)
    {
        Session::flash('success', 'Asignatura eliminada correctamente.');
        RelUsersSubject::find($request->id)->delete();
        return redirect()->route('relateSubjects',$request->subject_id)->with('success', 'Registro eliminado satisfactoriamente');
    }
}
