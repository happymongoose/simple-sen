<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Registry;
use App\Http\Requests\StoreTagRequest;
use App\Helpers\FunctionLibrary;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TagsController extends Controller
{
    private $registry;

    //Tag colours
    public $colours = [
      ["name"=>"blue", "rgb"=>"4e73df"],
      ["name"=>"indigo", "rgb"=>"6610f2"],
      ["name"=>"pink", "rgb"=>"e83e8c"],
      ["name"=>"red", "rgb"=>"e74a3b"],
      ["name"=>"orange", "rgb"=> "fd7e14"],
      ["name"=>"green", "rgb"=>"1cc88a"],
      ["name"=>"teal", "rgb"=>"20c9a6"],
      ["name"=>"cyan", "rgb"=>"36b9cc"],
      ["name"=>"grey", "rgb"=>"5a5c69"]
    ];

    //Constructor
    public function __construct() {

      //Authorised users only
      $this->middleware('auth');
      //Create registry
      $this->registry = new Registry();
    }

    /*
    * Bumps the next tag colour to use
    */
    public function bumpNextColour($bump=true) {

      //Get next colour
      $next_colour = $this->registry->getValue("tags.next_colour", 0)+1;

      //Deal with over-flow
      if (!isset($this->colours[$next_colour]))
        $next_colour=0;

      //Save in registry
      $this->registry->set("tags.next_colour", $next_colour);

      return $this->colours[$next_colour]["rgb"];

    }

    /*
    * Returns the next colour of tag to use
    *
    * @param $bump  true: use of this colour will be recorded
    *               false: use of this colour will not be recorded in the registry
    * @returns the colour in RGB format (without a preceding # char)
    *
    */
    public function getNewColour($bump=true) {

      //Get next colour
      $colour = $this->registry->getValue("tags.next_colour", 0);

      //Bump this value for next time?
      if ($bump)
        $this->bumpNextColour();

      return $this->colours[$colour]["rgb"];
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

      //Grab any search text passed by user
      $search_text = $request->input("search");

      //If no search text has been passed, show all tags...
      if ($search_text=="") {
        //Get all tags
        $tags = Tag::where('id', '>', 0)->orderBy('tag')->paginate($max_results);
        //Adjust pagination for maximum number of results per page
        $tags->appends([ 'max_results' => $max_results]);
      } else {

        //Break search text into an array
        $raw_search_strings = explode(" ", $search_text);

        //Do the same for the search string array
        $search_strings = [];
        foreach($raw_search_strings as $raw_search_string)
          $search_strings[]= "%" . $raw_search_string . "%";

        // Search by tag text or description
        $tags = Tag::Where(function ($query) use($search_strings) {
          for ($i=0; $i<count($search_strings); $i++) {
            $query->orWhere('tag', 'like', $search_strings[$i]);
            $query->orWhere('description', 'like', $search_strings[$i]);
          }
        })->orderBy('tag')->paginate();

        $tags->appends([ 'max_results' => $max_results]);

      }

      //Comma delimeted string containing all tags
      $all_tags_as_string = $this->getAllTagsAsString();

      //Create a string containing the tag colours
      $tag_colours_string_array = [];
      foreach ($this->colours as $value) {
        $tag_colours_string_array[] = "'" . $value["name"] . "': '#" . $value['rgb'] . "'";
      }
      $tag_colours_string = implode(",", $tag_colours_string_array);

      //Get next tag colour
      $next_tag_colour = $this->getNewColour(false);

      //Pass the current user to the view
      $user = auth()->user();

      //Show view
      return view('tags/index', compact('tags', 'max_results', 'all_tags_as_string', 'tag_colours_string', 'next_tag_colour', 'user'));

    }

    /**
     * Returns all of the tags in the database in the form "tag1,tag2,tag3"
     *
     * @return string
     */
    public function getAllTagsAsString() {

      //Grab all the tags
      $tags = Tag::all();
      //Create a simple table from the tags
      $tagTable = [];
      foreach ($tags as $tag) {
        $tagTable[] = "'" . $tag->tag . "'";
      }
      //Return the table as a string
      return implode(",", $tagTable);

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
    public function store(StoreTagRequest $request)
    {
      //Get request data
      $params = $request->all();

      //Get the tag to update
      $tag = Tag::create([
        'tag' => $params['tag'],
        'description' => $params['description'],
        'colour' => $params['colour']
      ]);

      //Update the next tag colour
      $this->bumpNextColour();

      //Redirect
      notify()->success("Tag '" . $params['tag'] . "' created");
      return redirect()->route('tags.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function show(Tag $tag)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(StoreTagRequest $request)
    {
        //Get request data
        $params = $request->all();

        //Get the tag to update
        $tag = Tag::find($params['id']);

        //Update the information
        $old_text = $tag->tag;
        $new_text = strtolower($params['tag']);
        $tag->tag = $new_text;
        $tag->description = $params['description'];
        $tag->colour = $params['colour'];

        //Save
        $tag->save();

        //Redirect
        notify()->success("Tag '" . $old_text . "' updated to '" . $new_text . "'");
        return redirect()->route('tags.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        //Get tag ID to delete
        $id = $request->input('id', null);
        if ($id==null) {
          notify()->error("An error occurred.");
          return redirect()->route('tags.index');
        }

        //Get the tag
        $tag = Tag::find($id);

        //Save the tag's text
        $tag_text = $tag->tag;

        //Remove this tag from any students
        DB::table("tag_student")->where('tag_id', $tag->id)->delete();

        //Remove this tag from any notes
        DB::table("tag_note")->where('tag_id', $tag->id)->delete();

        //Delete the tag
        $tag->delete();

        //Redirect
        notify()->success("Tag '" . $tag_text . "' deleted");
        return redirect()->route('tags.index');
    }


    /*
    * Returns tag information in JSON format
    *
    * @param $tag: tag to edit
    *
    */
    public function get(Tag $tag) {
      $tag->student_count = count($tag->students);
      return $tag;
    }

    /*
    * Outputs "true" if the tag text is not currently used
    *
    * @param $tag: tag to edit
    *
    */
    public function isUnique(Request $request) {

      //Grab text
      $text = strtolower($request->input("text", ""));
      $initial = strtolower($request->input("initial", ""));

      //If the tag hasn't changed, it's valid
      if ($text==$initial) return response()->json(true);

      $tag = Tag::where('tag', $text)->first();
      if ($tag!=null)
        return response()->json(false);
      else
        return response()->json(true);
    }

}
