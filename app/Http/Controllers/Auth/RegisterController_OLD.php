<?php

namespace pfg\Http\Controllers\Auth;

use pfg\Models\User;
use pfg\Models\Profesor;
use pfg\Models\Alumno;
use pfg\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterControllerOLD extends Controller
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
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \pfg\User
     */
    protected function create(array $data)
    {


       $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
       if($data['role']==2){
           $profesor = Profesor::create([
               'name' => $data['name'],
               'roles_id' => $data['role'],
               'dni' => '123'.$user->id,
               'users_id' => $user->id,

           ]);
           $profesor->user()->associate($user->id);
       }elseif($data['role']==3){
           $alumno = Alumno::create([
               'name' => $data['name'],
               'roles_id' => $data['role'],
               'dni' => '123'.$user->id,
               'users_id' => $user->id,

           ]);
           $alumno->user()->associate($user->id);
       }
       
        return $user;
    }
}
