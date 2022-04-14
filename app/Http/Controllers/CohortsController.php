<?php

namespace App\Http\Controllers;

use App\Models\Cohort;
use App\Models\CohortCondition;
use App\Models\YearGroup;
use App\Models\TeachingGroup;
use App\Models\Tag;
use App\Helpers\FunctionLibrary;

use Illuminate\Http\Request;

use App\Http\Controllers\TagsController;
use App\Http\Controllers\YearGroupController;
use App\Http\Controllers\TeachingGroupsController;
use App\Http\Requests\CreateCohortRequest;

class CohortsController extends Controller
{

  //-----------------------------------------------------------------------------------
  //
  // Conversion functions
  //
  //-----------------------------------------------------------------------------------

  //--------------------------
  //
  // Convert a comma delimited lists of year strings (e.g. "Year 1,Year 2,Year 3") to an array of IDs ("1,2,3")
  //
  //--------------------------

  public function convertYearStringsToIDs($strings) {
    //Convert strings into an array
    $year_array = explode(",", $strings);
    //Grab IDs from database
    $years = YearGroup::whereIn('name', $year_array)->pluck('year')->toArray();
    //Return array as string
    return implode(",",$years);
  }

  //--------------------------
  //
  // Convert a comma delimited lists of class strings (e.g. "Nursery,Reception,Year 1") to an array of IDs ("1,2,3")
  //
  //--------------------------

  public function convertTeachingGroupStringsToIDs($strings) {
    //Convert strings into an array
    $teaching_group_array = explode(",", $strings);
    //Grab IDs from database
    $teaching_groups = TeachingGroup::whereIn('name', $teaching_group_array)->pluck('id')->toArray();
    //Return array as string
    return implode(",",$teaching_groups);
  }

  //--------------------------
  //
  // Convert a comma delimited lists of tag strings (e.g. "ehcp,lac,learning") to an array of IDs ("1,2,3")
  //
  //--------------------------

  public function convertTagStringsToIDs($strings) {
    //Convert strings into an array
    $tag_array = explode(",", $strings);
    //Grab IDs from database
    $tags = Tag::whereIn('tag', $tag_array)->pluck('id')->toArray();
    //Return array as string
    return implode(",",$tags);
  }


  //-----------------------------------------------------------------------------------
  //
  // Access pages
  //
  //-----------------------------------------------------------------------------------


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

        //Grab any search text passed by user
        $search_text = $request->input("search");

        //Get list of all cohorts
        $cohorts = Cohort::where('id', '>', 0)->orderBy('description')->paginate($max_results);

        //Load the students for each cohort
        foreach($cohorts as $cohort)
          $cohort->getStudents();

        //Pass the current user to the view
        $user = auth()->user();

        //Show view
        return view('cohorts/index', compact('cohorts', 'max_results', 'user'));

    }

    //--------------------------
    //
    // View a cohort
    //
    //--------------------------

    public function view(Cohort $cohort, Request $request) {

      //Instantiate a function library
      $functionLibrary = new FunctionLibrary;

      //Get the maximum number of rows for the table
      $max_results = $functionLibrary->getTableMaxRows($request);

      //Get paginated collection of students for this cohort
      $students = $cohort->getPaginatedStudents($max_results);

      //Pass the current user to the view
      $user = auth()->user();

      //Show view
      return view('cohorts/view', compact('cohort', 'max_results', 'user'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Set the editing flag
        $editing = false;

        //Get the current user
        $user = auth()->user();

        //Only admin users have access to this route
        if (!($user->isAdmin())) {
          notify()->error("Access denied");
          return redirect()->route('tags.index');
        }

        //Get all the available tags as a string
        $tagsController = new TagsController();
        $all_tags_as_string = $tagsController->getAllTagsAsString();

        //Get all year groups as a string
        $yearGroupController = new YearGroupController();
        $all_year_groups_as_string = $yearGroupController->getAllAsString();

        //Get all classes as a string
        $teachingGroupController = new TeachingGroupsController();
        $all_teaching_groups_as_string = $teachingGroupController->getAllAsString();

        //Show view
        return view('cohorts/edit', compact('user', 'all_tags_as_string', 'all_year_groups_as_string', 'all_teaching_groups_as_string', 'editing'));

    }


    //--------------------------
    //
    // Check that at least one rule for the cohort has been passed
    //
    //--------------------------

    public function validateCohortConditions($data) {

      //Check at least one rule passed
      $required = ['input-year-groups', 'input-classes', 'input-all-tags', 'input-any-tag'];
      $rule_given = false;
      foreach ($data as $key=>$value) {
        if ( (in_array($key, $required)) && ($value!=null) ) {
          $rule_given=true;
          break;
        } //end if
      } //end for

      return $rule_given;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCohortRequest $request)
    {
        //--------------------------
        //
        // Validation
        //
        //--------------------------

        //Get data from request
        $data = $request->all();

        //Check at least one condition was passed
        if (!$this->validateCohortConditions($data)) {
          notify()->error("No rules specified for cohort");
          return redirect()->route('cohorts.create');
        }

        //--------------------------
        //
        // Cohort creation
        //
        //--------------------------

        //Create the new cohort
        $cohort = Cohort::create([
          'description' => $data['description']
        ]);
        $cohort->save();

        //Add condition lines (as necessary)
        if ($data['input-year-groups']!=null)
          $cohort->addCondition('year_group', 'any', $this->convertYearStringsToIDs($data['input-year-groups']));
        if ($data['input-classes']!=null)
          $cohort->addCondition('class', 'any', $this->convertClassStringsToIDs($data['input-classes']));
        if ($data['input-all-tags']!=null)
          $cohort->addCondition('tags', 'all', $this->convertTagStringsToIDs($data['input-all-tags']));
        if ($data['input-any-tag']!=null)
          $cohort->addCondition('tags', 'any', $this->convertTagStringsToIDs($data['input-any-tag']));

        //Notify success and redirect
        notify()->success("Cohort created");
        return redirect()->route('cohorts.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cohort  $cohort
     * @return \Illuminate\Http\Response
     */
    public function show(Cohort $cohort)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cohort  $cohort
     * @return \Illuminate\Http\Response
     */
    public function edit(Cohort $cohort)
    {

      //Set the editing flag
      $editing = true;

      //Get the current user
      $user = auth()->user();

      //Only admin users have access to this route
      if (!($user->isAdmin())) {
        notify()->error("Access denied");
        return redirect()->route('tags.index');
      }

      //Get all the available tags as a string
      $tagsController = new TagsController();
      $all_tags_as_string = $tagsController->getAllTagsAsString();

      //Get all year groups as a string
      $yearGroupController = new YearGroupController();
      $all_year_groups_as_string = $yearGroupController->getAllAsString();

      //Get all classes as a string
      $teachingGroupController = new TeachingGroupsController();
      $all_teaching_groups_as_string = $teachingGroupController->getAllAsString();

      $cohort->addConditionFriendlyValueFields();

      //Show view
      return view('cohorts/edit', compact('user', 'all_tags_as_string', 'all_year_groups_as_string', 'all_teaching_groups_as_string', 'cohort', 'editing'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cohort  $cohort
     * @return \Illuminate\Http\Response
     */
    public function update(CreateCohortRequest $request, Cohort $cohort)
    {

      //--------------------------
      //
      // Validation
      //
      //--------------------------

      //Get data from request
      $data = $request->all();

      //Check at least one condition was passed
      if (!$this->validateCohortConditions($data)) {
        notify()->error("No rules specified for cohort");
        return redirect()->route('cohorts.create');
      }

      //--------------------------
      //
      // Update cohort
      //
      //--------------------------

      //Update data
      $cohort->description = $data['description'];
      $cohort->save();

      //Delete old conditions
      $cohort->deleteAllConditions();

      //Update conditions
      if ($data['input-year-groups']!=null)
        $cohort->addCondition('year_group', 'any', $this->convertYearStringsToIDs($data['input-year-groups']));
      if ($data['input-classes']!=null)
        $cohort->addCondition('class', 'any', $this->convertClassStringsToIDs($data['input-classes']));
      if ($data['input-all-tags']!=null)
        $cohort->addCondition('tags', 'all', $this->convertTagStringsToIDs($data['input-all-tags']));
      if ($data['input-any-tag']!=null)
        $cohort->addCondition('tags', 'any', $this->convertTagStringsToIDs($data['input-any-tag']));

      //Notify success and redirect
      notify()->success("Cohort updated");
      return redirect()->route('cohorts.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cohort  $cohort
     * @return \Illuminate\Http\Response
     */
    public function delete(Cohort $cohort)
    {
        //Delete all the conditions associated with the cohort
        $cohort->deleteAllConditions();
        //Delete the cohort itself
        $cohort->delete();
        //Return to the cohort list
        notify()->success("Cohort deleted");
        return redirect()->route('cohorts.index');

    }
}
