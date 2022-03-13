<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Registry;

use App\Models\Student;
use App\Models\Tag;


class HomeController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //Authorised users only
        $this->middleware('auth');
//        $this->middleware('role:admin,user');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        //Get user
        $user = Auth::user();

//        notify()->success('Hello', 'Welcome');
//        notify()->error('Hello', 'Welcome');
//        notify()->info('Hello', 'Welcome');
//        notify()->warning('Hello', 'Welcome');

        //Return view
        return view('home', compact('user'))->with("Title", "text");

    }

    /**
     * Logout
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');

    }

}
