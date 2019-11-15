<?php

namespace pfg\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use pfg\Mail\UserCreateMail;
use pfg\Models\User;
use Session;
use Illuminate\Support\Facades\Hash;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	public function restablishPass(request $req)
	{
		$token = $req->token;
		$user = DB::table('users')->where('token', $token)->first();
		if ($user) {
			$user = get_object_vars($user);
			$idUser = $user['id'];
			$path = $req->path();
			return view('layouts.remember', compact('idUser', 'path'));
		}
        Session::flash('error', 'Ya se ha usado el token para reestablecer la contraseña');
        return redirect()->route('login');
	}

	public function remember(request $req)
	{
		Session::flash('redirect', 'teacherassignment');

		$path = $req->input('path');
		$userId = intval($req->input('user'));
		$pass = $req->input('password');
		$confirmpass = $req->input('confirmpassword');
		if ($pass == $confirmpass) {
			DB::table('users')
				->where('id', $userId)
				->update(array('token' => ''));
			DB::table('users')
				->where('id', $userId)
				->update(array('password' => Hash::make($pass)));
			Session::flash('success', 'Se ha realizado el cambio de contraseña correctamente');
			return redirect('/login');
		} else {
			Session::flash('error', '¡Las contraseñas no coinciden!');
			return redirect($path);
		}
	}

	public function newpassword(Request $request)
    {
        $user = User::where('email',$request->email)->first();
        if(!$user)
        {
            $validatedData = array('email'=>'El email no existe');
            return back()->withInput()->withErrors($validatedData);
        }
        $token = Str::random();
        User::where('email',$request->email)
            ->where('id',$user->id)
            ->update(['token'=>$token]);
        $user = User::where('email',$request->email)->first();
        User::crearPdf($user);
        Mail::to($user)->send(new UserCreateMail($user));
        Session::flash('success', 'Se ha enviado un email con su nuevo acceso a la plataforma');
        return redirect('/login');

    }
}
