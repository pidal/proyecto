<?php

namespace pfg\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Session::has('errors') && Session::has('redirect')) {
            //dd(Session::all());
            return redirect()->to(Session::get('redirect'))->with('errors', Session::get('errors'))
            ->withInput(Session::getOldInput());
        }
        return redirect()->to(url('/login'));
    }
}
