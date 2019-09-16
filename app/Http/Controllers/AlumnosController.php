<?php

namespace pfg\Http\Controllers;

use pfg\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AlumnosController extends Controller
{

    const ROLE_ALUMNO = 3;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('CheckTeacher');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $alumnos = User::where('roles_id', User::ROLE_ALUMNO)->orderBy('id', 'DESC')->paginate(7);
        return view('alumnos.index', compact('alumnos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('alumnos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->roles_id = User::ROLE_ALUMNO;
        $this->validate($request, [
            'name' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($request->id)
            ],
            'dni' => 'required|numeric',
            'surname' => 'required',
            'roles_id' => 'required|numeric'
        ]);
        User::create($request->all());
        return redirect()->route('alumnos.index')->with('success', 'Registro creado satisfactoriamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  \pfg\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $alumnno = User::find($id);
        return view('alumnos.show', compact('alumno'));
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
        return view('alumnos.edit', compact('alumno'));
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
        $request->roles_id = User::ROLE_ALUMNO;
        $this->validate($request, [
            'name' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($request->id)
            ],
            'dni' => 'required|numeric',
            'surname' => 'required',
            'roles_id' => 'required|numeric'
        ]);
        User::find($id)->update($request->all());
        return redirect()->route('alumnos.index')->with('success', 'Registro Actualizado satisfactoriamente');
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
        return redirect()->route('alumnos.index')->with('success', 'Registro eliminado satisfactoriamente');
    }
}
