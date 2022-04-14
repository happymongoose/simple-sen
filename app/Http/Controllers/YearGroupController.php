<?php

namespace App\Http\Controllers;

use App\Models\YearGroup;
use App\Models\Student;
use App\Helpers\FunctionLibrary;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class YearGroupController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

      //Instantiate a function library
      $functionLibrary = new FunctionLibrary;

      //Get the maximum number of rows for the table
      $max_results = $functionLibrary->getTableMaxRows($request);

      //Grab a list of all the teaching groups
      $year_groups = YearGroup::where('id', '>', 0)->orderBy('year')->paginate($max_results);

      //Adjust pagination for maximum number of results per page
      $year_groups->appends([ 'max_results' => $max_results]);

      //Get user
      $user=auth()->user();

      //Show view
      return view('year_groups/index', compact('year_groups', 'max_results', 'user'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\YearGroup  $yearGroup
     * @return \Illuminate\Http\Response
     */
    public function show(YearGroup $yearGroup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\YearGroup  $yearGroup
     * @return \Illuminate\Http\Response
     */
    public function edit(YearGroup $yearGroup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\YearGroup  $yearGroup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, YearGroup $yearGroup)
    {
        //Get the data
        $params = $request->all();

        //Get the specified year group
        $group = YearGroup::find($params['id']);
        $old_name = $group->name;

        //Update info
        $group->name = $params['year-group-name'];
        $group->show = $request->input('year-group-show', 0);

        //Save
        $group->save();

        //Redirect
        notify()->success("Year group '" . $old_name . "' updated");
        return redirect()->route('year_groups.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\YearGroup  $yearGroup
     * @return \Illuminate\Http\Response
     */
    public function destroy(YearGroup $yearGroup)
    {
        //
    }


    //-----------------------------------------
    //
    // Returns all the year groups as a string
    //
    //-----------------------------------------

    public function getAllAsString() {

      //Get year groups
      $yearGroups = YearGroup::where('show', true)->get()->sortBy('year');

      //Create a simple table from the year groups
      $yearGroupTable = [];
      foreach ($yearGroups as $yearGroup) {
        $yearGroupTable[] = "'" . $yearGroup->name . "'";
      }
      //Return the table as a string
      return implode(",", $yearGroupTable);
    }


    //-----------------------------------------
    //
    // Returns this year group as JSON
    //
    //-----------------------------------------

    public function get(YearGroup $group) {
      return $group;
    }

    //-----------------------------------------
    //
    // Show students in this year group
    //
    //-----------------------------------------

    public function view(YearGroup $group, Request $request) {

      //Instantiate a function library
      $functionLibrary = new FunctionLibrary;

      //Get the maximum number of rows for the table
      $max_results = $functionLibrary->getTableMaxRows($request);

      //Grab any search text passed by user
      $search_text = $request->input("search");

      //Get students for this year group
      if ($search_text=="")
        $students = Student::where('year_group', $group->year)->orderBy('first_name')->paginate($max_results);
      else {

        //Start by searching text in students first name or last name
        $student_ids_collection = DB::Table("students")->select("*");
        $search_strings = explode(" ", $search_text);
        $key=0;
        foreach ($search_strings as $key => $search_string) {
          $search_string = "%" . $search_string . "%";
          if ($key==0)
            $student_ids_collection->where('first_name', 'like', $search_string)->orWhere('last_name', 'like', $search_string);
          else
            $student_ids_collection->orWhere('first_name', 'like', $search_string)->orWhere('last_name', 'like', $search_string);
          $key++;
        }
        $student_ids_simple = $student_ids_collection->get()->pluck('id')->toArray();

        //Get all students IDs in the correct year group
        $student_ids_year_group = Student::where('year_group', $group->year)->pluck('id')->toArray();

        //Get IDs of students that feature in both sets
        $student_ids = array_intersect($student_ids_simple, $student_ids_year_group);

        //Grab those students
        $students = Student::whereIn('id', $student_ids)->orderBy('first_name')->paginate($max_results);
      }


      //Show view
      return view('year_groups/view', compact('students', 'max_results', 'group'));

    }


}
