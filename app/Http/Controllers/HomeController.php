<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        if($user->userTypeID == 1)
            return view('student\home');
        if($user->userTypeID == 2 || $user->userTypeID == 5)
            return view('leader\home');
        if($user->userTypeID == 3)
            return view('examiner\home');
        if($user->userTypeID == 4)
            return view('admin\home');
    }
    
}
