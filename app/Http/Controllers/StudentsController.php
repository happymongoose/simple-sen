<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

use App\Models\Student;
use App\Models\Tag;
use App\Models\Note;
use App\Models\TeachingGroup;
use App\Models\YearGroup;

use App\Http\Controllers\TagsController;
use App\Http\Requests\UpdateStudentRequest;
use App\Http\Requests\CreateStudentRequest;

class StudentsController extends Controller
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


  //-----------------------------------------------------------------------------------
  //
  // Search by a student's note by a set of tags
  //
  //-----------------------------------------------------------------------------------

  public function searchNotes($student, $tag_string="") {

    //If no tags have been passed, just return all of the tags
    $tag_string = strtolower(trim($tag_string));
    if ($tag_string=="")
      return Note::where('class', 'student')->where('instance_id', $student->id)->orderBy('created_at', 'desc')->get();


    //Convert tag string into an array
    $tag_array = explode(" ", $tag_string);

    //Get a count of how many tags the query has to return
    $tag_count = count($tag_array);

    //Get all the tag IDs matching the search strings
    $tag_ids = Tag::whereIn('tag', $tag_array)->pluck('id')->toArray();

    //Get all the notes belonging to this student
    $note_ids = Note::where('class', 'student')->where('instance_id', $student->id)->pluck('id')->toArray();

    //Now get all the notes that have one of the relevant tag ids
    $all_notes = DB::table('tag_note')->whereIn('tag_id', $tag_ids)->whereIn('note_id', $note_ids)->orderBy('note_id')->pluck('note_id')->toArray();

    //Count how often each note appears
    $note_count = array_count_values($all_notes);

    //Create final array of qualifying note IDs
    $note_ids = [];
    foreach ($note_count as $key=>$count) {
      if ($count==$tag_count)
        $note_ids[] = $key;
    }

    //Grab the relevant rows
    $notes = Note::whereIn('id', $note_ids)->orderBy('created_at', 'desc')->get();

    return $notes;

  }


  //-----------------------------------------------------------------------------------
  //
  // Index
  //
  //-----------------------------------------------------------------------------------

  public function index(Request $request) {

    //Get the pagination length (if set)
    $max_results = $request->input("max_results", 20);

    //Grab any search text passed by user
    $search_text = $request->input("search");

    //If no search text has been passed, show all students...
    if ($search_text=="") {
      //Grab a list of all the teaching groups
      $students = Student::where('id', '>', 0)->orderBy('first_name')->paginate($max_results);
      //Adjust pagination for maximum number of results per page
      $students->appends([ 'max_results' => $max_results]);
    } else {

      //Break search text into an array
      $raw_search_strings = explode(" ", $search_text);

      //Do the same for the search string array
      $search_strings = [];
      foreach($raw_search_strings as $raw_search_string)
        $search_strings[] = "%" . $raw_search_string . "%";

      //-----------------------------------
      //
      // Search by personal information
      //
      //-----------------------------------

      //Start by searching text in students personal information
      $student_ids_collection = DB::Table("students")->select("*");
      $skip_count=0;
      foreach ($search_strings as $key => $search_string) {

        //Ignore tag only searches
        if (str_starts_with($search_string, "%tag:" )) {
          $skip_count++;
          continue;
        }

        if ($key==0)
          $student_ids_collection->where('first_name', 'like', $search_string)->orWhere('last_name', 'like', $search_string);
        else
          $student_ids_collection->orWhere('first_name', 'like', $search_string)->orWhere('last_name', 'like', $search_string);
      }
      if ($skip_count<count($search_strings))
        $student_ids_simple = $student_ids_collection->get()->pluck('id')->toArray();
      else
        $student_ids_simple = [];

      //-----------------------------------
      //
      // Search by tag
      //
      //-----------------------------------

      //Get any tags matching the search string
      $tags_collection = DB::Table("tags")->select("*");
      foreach ($search_strings as $key => $search_string) {

        //If this is a tag specific search (in the form %tag:adhd%), remove the 'tag:' prefix (so it becomes %adhd%)
        //Also remove % % prefix, because want to search for exact match
        if (str_starts_with($search_string, "%tag:"))
          $search_string = substr($search_string, 5, -1);

        if ($key==0)
          $tags_collection->where('tag', 'like', $search_string);
        else
          $tags_collection->orWhere('tag', 'like', $search_string);
      }
      $tag_ids = $tags_collection->get()->pluck('id');

      //Find any students with one of those tags
      $student_ids_tags = DB::table('tag_student')->whereIn('tag_id', $tag_ids)->get()->pluck('student_id')->toArray();
      $student_ids = array_merge($student_ids_simple, $student_ids_tags);

      //Grab students and merge with previous query
      $students = Student::whereIn('id', $student_ids)->orderBy('first_name')->paginate($max_results);
      $students->appends([ 'max_results' => $max_results]);

    }

    //Get all the available tags as a string
    $tagsController = new TagsController();
    $all_tags_as_string = $tagsController->getAllTagsAsString();

    //Get all of the year groups
    $year_groups = YearGroup::all();

    //Grab a list of all the teaching groups
    $teaching_groups = TeachingGroup::where('id', '>', 0)->orderBy('name')->paginate($max_results);

    //Show view
    return view('students/index', compact('students', 'max_results', 'teaching_groups', 'year_groups', 'all_tags_as_string'));

  }


  //-----------------------------------------------------------------------------------
  //
  // Edit
  //
  //-----------------------------------------------------------------------------------

  public function edit(Student $student, Request $request) {

    //Signify this is an edit (instead of creating a new student)
    $edit=true;

    //Get all the available tags as a string
    $tagsController = new TagsController();
    $all_tags_as_string = $tagsController->getAllTagsAsString();

    //Sort notes
    $notes = $this->searchNotes($student, urldecode($request->input('note-search')));

    //Get all of the year groups
    $year_groups = YearGroup::all();

    //Class list
    $teaching_groups = TeachingGroup::all(['id', 'name']);

    return view('students/edit', compact('student', 'edit', 'all_tags_as_string', 'notes', 'teaching_groups', 'year_groups'));

  }


  public function update(Student $student, UpdateStudentRequest $request) {

    //Get request data
    $params = $request->all();

    //Copy across data

    //Do set tags first, as this will reload the student model
    $student->setTags($params['student-tags']);

    //Reset first and last name
    $student->first_name = $params['first_name'];
    $student->last_name = $params['last_name'];
    $student->teaching_group_id = $params['teaching-group'];
    $student->year_group = $params['year-group'];
    $student->save();

    //Redirect
    notify()->success("Student '" . $student->getFullName() . "' updated");
    return redirect()->route('students.edit', $student->id);

  }

  //-----------------------------------------------------------------------------------
  //
  // Store
  //
  //-----------------------------------------------------------------------------------

  public function store(CreateStudentRequest $request) {

    //Get request data
    $params = $request->all();

    $student = Student::Create([
      'first_name' => $params['first_name'],
      'last_name' => $params['last_name'],
      'year_group' => $params['year-group'],
      'teaching_group_id' => $params['teaching-group']
    ]);

    $student->addTags($params['student-tags']);
    $student->save();

    notify()->success("Student '" . $student->getFullName() . "' created");
    return redirect()->route('students.index');

  }

  //-----------------------------------------------------------------------------------
  //
  // Delete
  //
  //-----------------------------------------------------------------------------------


  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Student  $student
   * @return \Illuminate\Http\Response
   */
  public function delete(Student $student, Request $request)
  {
      //Get the return path
      $return_path = $request->input('return-route', route('students.index'));

      //Save the students name
      $name = $student->getFullName();

      //Delete any notes associated with the student
      $note_ids = Note::where('class', 'student')->where('instance_id', $student->id)->get();
      Note::destroy($note_ids);

      //Delete the student
      $student->delete();

      //Redirect
      notify()->success("Student '" . $name . "' deleted");
      return redirect($return_path);
  }


}
