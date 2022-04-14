<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Registry;

use App\Models\Student;
use App\Models\Tag;
use App\Models\YearGroup;
use Database\Seeders\TagSeeder;

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

    public function install(Request $request) {

      //Pass the current user to the view
      $user = auth()->user();

      //This page can only be viewed by the admin user
      if (!$user->isAdmin())
        die();

      //Show view
      return view('installation/page1', compact('user'));

    }

    public function installPage2(Request $request) {

      //If the user opted for no help, record their choice in the registry and redirect to the home page
      if ($request->input('installation_help', 'yes')=="no") {
        //Record in registry
        $registry = new Registry;
        $registry->set("first-run", false, "boolean");
        //Redirect
        return redirect(route('home'));
      }

      //Show view
      return view('installation/page2');

    }

    public function installPage3(Request $request) {

      //School name passed? If not, send back to previous page
      $school_name = $request->input('school-name');
      if ($school_name=="")
        return redirect(route('install_page2'));

      //Save school name
      $registry = new Registry;
      $registry->set("school-name", $school_name, "string");

      //Get year groups
      $year_groups = YearGroup::all();

      //Show view
      return view('installation/page3', compact('year_groups'));

    }

    public function installPage4(Request $request) {

      //Get the youngest and oldest year groups
      $low = $request->input('age-low');
      $high = $request->input('age-high');

      //If the variables are passed the wrong way around, swap them over
      if ($low>$high) {
        $temp = $low; $low=$high; $high=$temp;
      }

      //Hide / show year groups as necessary
      YearGroup::where('year', '<', $low)->orWhere('year', '>', $high)->update(['show' => false]);
      YearGroup::where('year', '>=', $low)->where('year', '<=', $high)->update(['show' => true]);

      //Show the next page
      return view('installation/page4');

    }

    public function installPage5(Request $request) {

      //Add common tags?
      $add_tags = strtolower($request->input('tags', 'yes'));

      //If adding tags, use the tag seeder object to add common tags
      if ($add_tags=="yes") {
        $seeder = new TagSeeder;
        $seeder->run();
      }

      //Record in registry that the installation process has been completed
      $registry = new Registry;
      $registry->set("first-run", false, "boolean");

      //Show the next page
      return view('installation/page5');

    }

}
