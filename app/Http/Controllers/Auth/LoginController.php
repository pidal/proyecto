<?php

namespace pfg\Http\Controllers\Auth;

use pfg\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
	/*
	|--------------------------------------------------------------------------
	| Login Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles authenticating users for the application and
	| redirecting them to your home screen. The controller uses a trait
	| to conveniently provide its functionality to your applications.
	|
	*/

	protected $redirectTo = '/';

	use AuthenticatesUsers;

	/**
	 * Where to redirect users after login.
	 *
	 * @var string
	 */
	protected function authenticated(Request $request, $user)
	{
		if ($user->roles_id == 1) {
			return redirect('/adminalumnos');
		} else if ($user->roles_id == 2) {
			return redirect('/teacherassignment');
		} else if ($user->roles_id == 3) {
			return redirect('/showSubjectsStudent');
		} else {
			return redirect('/perdida');
		}
	}


	public function logout(Request $request)
	{
		$request->session()->flush();
		Auth::logout();
		return redirect('/');
	}

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest')->except('logout');
	}

}
