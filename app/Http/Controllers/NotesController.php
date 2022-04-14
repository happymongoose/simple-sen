<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;


use App\Models\Note;
use App\Models\Tag;
use App\Models\User;
use App\Helpers\FunctionLibrary;

use App\Http\Requests\StoreNoteRequest;
use App\Http\Controllers\TagsController;

class NotesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(StoreNoteRequest $request)
    {
      //Get request data
      $params = $request->all();

      //Handle allow edit checkbox
      if (!isset($params['note-allow-edit']))
        $params['note-allow-edit']=0;

      //Create new note
      $note = Note::create([
        'title' => $params['note-title'],
        'text' => $params['note-text'],
        'colour' => $params['note-colour'],
        'class' => $params['note-class'],
        'instance_id' => $params['note-instance-id'],
        'user_id' => Auth::user()->id,
        'allow_edit' => $params['note-allow-edit']
      ]);

      //Set tags
      $note->setTags($params['note-tags']);

      //Redirect
      notify()->success("Note created");
      return redirect($params['note-return-url']);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function show(Note $note)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function edit(Note $note)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function update(StoreNoteRequest $request, Note $note)
    {
      //Get request data
      $params = $request->all();

      //Get the note to update
      $note = Note::find($params['note-id']);

      //Update the information
      $note->title = $params['note-title'];
      $note->text = $params['note-text'];
      $note->colour = $params['note-colour'];
      $note->class = $params['note-class'];
      $note->instance_id = $params['note-instance-id'];
      $note->allow_edit = $request->input('note-allow-edit', 0);

      //Save
      $note->save();
      $note->setTags($params['note-tags']);

      //Redirect
      notify()->success("Note updated");
      return redirect($params['note-return-url']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
      //Get request data
      $params = $request->all();

      //Get the note to update
      $note = Note::find($params['note-id']);

      //Only admins and the owner can delete the note
      $user = Auth::user();
      if ( (!$user->isAdmin()) && ($user->id!=$note->user_id)  ) {
        notify()->error("Permission denied");
        return redirect($params['note-return-url']);
      }

      //Delete it
      $note->delete();

      //Redirect
      notify()->success("Note deleted");
      return redirect($params['note-return-url']);

    }

    /*
    * Returns note information in JSON format
    *
    * @param $note: note to edit
    *
    */
    public function get(Note $note) {
      //Add tag string
      $note->tag_string = $note->getTagsAsString();
      //Add whether the user has full rights to note
      $note->full_access_rights = $note->userHasFullAccessRights() == true;
      $note->user_owns = $note->user_id==Auth::user()->id;
      return $note;
    }

    //-----------------------------------------------------------------------------------
    //
    // Search by a user's notes by a set of tags
    //
    //-----------------------------------------------------------------------------------

    public function searchNotes($user, $tag_string="", $max_results) {

      //If no tags have been passed, just return all of the tags
      $tag_string = strtolower(trim($tag_string));
      if ($tag_string=="")
        return Note::where('class', 'user')->where('instance_id', $user->id)->orderBy('created_at', 'desc')->paginate($max_results);


      //Convert tag string into an array
      $tag_array = explode(" ", $tag_string);

      //Get a count of how many tags the query has to return
      $tag_count = count($tag_array);

      //Get all the tag IDs matching the search strings
      $tag_ids = Tag::whereIn('tag', $tag_array)->pluck('id')->toArray();

      //Get all the notes belonging to this student
      $note_ids = Note::where('class', 'user')->where('instance_id', $user->id)->pluck('id')->toArray();

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
      $notes = Note::whereIn('id', $note_ids)->orderBy('created_at', 'desc')->paginate($max_results);

      return $notes;

    }

    //-----------------------------------------------------------------------------------
    //
    // User takes ownership of note (must be an admin to do this)
    //
    //-----------------------------------------------------------------------------------

    public function takeOwnership(Request $request) {

      //If user isn't admin, reject
      $user = auth()->user();
      if (!$user->isAdmin()) {
        notify()->error("Only admin users can take ownership of notes.");
        return redirect($params['note-return-url']);
      }

      //Update owner of note
      $params = $request->all();
      $note = Note::find($params['note-id']);
      $note->user_id = $user->id;
      $note->save();

      notify()->success("Ownership of note changed to '" . $user->name . "'.");
      return redirect($params['note-return-url']);

    }


    //-----------------------------------------------------------------------------------
    //
    // Noteboard / "My notes"
    //
    //-----------------------------------------------------------------------------------

    public function userNotesIndex(Request $request)
    {
      //Grab the current user
      $user = auth()->user();

      //Instantiate a function library
      $functionLibrary = new FunctionLibrary;

      //Get the maximum number of rows for the table
      $max_results = $functionLibrary->getTableMaxRows($request);

      //Grab notes for the authorised user
      $notes = $this->searchNotes($user, urldecode($request->input('note-search')), $max_results);


      //Get all the available tags as a string
      $tagsController = new TagsController();
      $all_tags_as_string = $tagsController->getAllTagsAsString();

      //Show view
      return view('notes/user_index', compact('notes', 'user', 'max_results', 'all_tags_as_string'));

    }


}
