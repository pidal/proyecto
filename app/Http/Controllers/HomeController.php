<?php

namespace pfg\Http\Controllers;

use Illuminate\Http\Request;
use Session;


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
            return redirect()->to(Session::get('redirect'))->with('errors', Session::get('errors'))
            ->withInput(Session::getOldInput());
        }

        //dd(Session::all());

        if (Session::has('errors') && Session::get('_previous')['url'] == 'https://www.joaquin-mateos.site/password/reset') {
            
            return redirect()->to(url('/password/reset'))->with('errors', Session::get('errors'))
            ->withInput(Session::getOldInput());
        }

        return redirect()->to(url('/login'));
    }
}
