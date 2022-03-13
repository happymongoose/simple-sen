<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\TeachingGroup;
use App\Models\Student;
use App\Http\Requests\StoreTeachingGroupRequest;

//TODO: Move individual student to another class
//TODO: Move whole class to another class


class TeachingGroupsController extends Controller
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

  /*
  * Returns tag information in JSON format
  *
  * @param $tag: tag to edit
  *
  */
  public function get(TeachingGroup $group) {
    return $group;
  }

  //-----------------------------------------------------------------------------------
  //
  // Index page
  //
  //-----------------------------------------------------------------------------------

  public function index(Request $request) {

    //Get the pagination length (if set)
    $max_results = $request->input("max_results", 20);

    //Grab a list of all the teaching groups
    $teaching_groups = TeachingGroup::where('id', '>', 0)->orderBy('name')->paginate($max_results);

    //Adjust pagination for maximum number of results per page
    $teaching_groups->appends([ 'max_results' => $max_results]);

    //Get user
    $user=auth()->user();

    //Show view
    return view('teaching_groups/index', compact('teaching_groups', 'max_results', 'user'));

  }

  //-----------------------------------------------------------------------------------
  //
  // Delete
  //
  //-----------------------------------------------------------------------------------


  public function delete(TeachingGroup $group) {

    //Save group name
    $name = $group->name;

    //Move any children in this group into the "unallocated" group (which always has ID 1)
    Student::where('teaching_group_id', $group->id)->update(['teaching_group_id' => 1]);

    //Delete the teaching group
    $group->delete();

    //Redirect
    notify()->success("Class '" . $name . "' deleted");
    return redirect()->route('teaching_groups.index');


  }

  //-----------------------------------------------------------------------------------
  //
  // Edit
  //
  //-----------------------------------------------------------------------------------

  public function edit(TeachingGroup $group, Request $request) {

    //Signify this is an edit
    $edit=true;

    //Get the pagination length (if set)
    $max_results = $request->input("max_results", 30);

    //Sort students
    $students = Student::where('teaching_group_id', $group->id)->orderBy('first_name')->orderBy('last_name')->get();

    //List of all teaching groups
    $teaching_groups = TeachingGroup::all(['id', 'name']);

    return view('teaching_groups/edit', compact('group', 'edit', 'max_results', 'students', 'teaching_groups'));

  }

  //Removes the student from the teaching group and places them in the 'unallocated' group
  public function removeStudent(TeachingGroup $group, Student $student) {

    $student->teaching_group_id=1;
    $student->save();

    //Redirect
    notify()->success("They're now in the 'unallocated' teaching group.", "Removed " . $student->getFullName() . ".", );
    return redirect()->route('teaching_groups.edit', $group->id, 'teaching_groups');


  }


  //--------------------------
  //
  // Store
  //
  //--------------------------

  public function store(StoreTeachingGroupRequest $request) {

    //Get request data
    $params = $request->all();

    $group = TeachingGroup::create([
        'name' => $params['name'],
      ]);

    //Redirect
    notify()->success("Class '" . $params['name'] . "' created");
    return redirect()->route('teaching_groups.index');

  }


  //--------------------------
  //
  // Update
  //
  //--------------------------

  public function update(StoreTeachingGroupRequest $request) {

    //Get request data
    $params = $request->all();

    //Update data
    $group = TeachingGroup::find($params['id']);
    $original_name = $group->name;
    $group->name = $params['name'];
    $group->save();

    //Redirect
    notify()->success("Class '" . $original_name . "' updated");
    return redirect()->route('teaching_groups.index');

  }

  //-----------------------------------------
  //
  // Returns all the classes as a comma delimited string
  //
  //-----------------------------------------

  public function getAllAsString() {

    $teachingGroups = TeachingGroup::all()->sortBy('name');
    //Create a simple table from the year groups
    $teachingGroupTable = [];
    foreach ($teachingGroups as $teachingGroup) {
      $teachingGroupTable[] = "'" . $teachingGroup->name . "'";
    }
    //Return the table as a string
    return implode(",", $teachingGroupTable);
  }

  //-----------------------------------------------------------------------------------
  //
  // Show all the students in the specified teaching group
  //
  //-----------------------------------------------------------------------------------

  public function view(TeachingGroup $group, Request $request) {

    //Get the pagination length (if set)
    $max_results = $request->input("max_results", 20);

    //Grab a list of all the teaching groups
    $students = Student::where('teaching_group_id', $group->id)->orderBy('first_name')->orderBy('last_name')->paginate($max_results);

    //Get user
    $user=auth()->user();

    //Show view
    return view('teaching_groups/view', compact('group', 'students', 'user', 'max_results'));



  }



}
