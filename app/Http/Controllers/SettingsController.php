<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Registry;

class SettingsController extends Controller
{

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      //Authorised users only
      $this->middleware('role:admin');

  }

  public function index(Request $request) {

    //Get the current user
    $user=auth()->user();

    //Create a registry object and send to view
    $registry = new Registry;

    //Show view
    return view('settings.index', compact('user', 'registry'));

  }


  //-----------------------------------------------------------------------------------
  //
  // Saves changes to settings
  //
  //-----------------------------------------------------------------------------------

  public function update(Request $request) {

    //Grab a registry object
    $registry = new Registry;

    $school_name = $request->input('school-name', "");

    if ($school_name!="") {
        $registry->set("school-name", $school_name);
        notify()->success("Settings updated");
    }

    //Return back to settings index page
    return redirect()->route('settings.index');

  }

}
