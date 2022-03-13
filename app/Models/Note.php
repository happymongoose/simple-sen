<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use ESolution\DBEncryption\Traits\EncryptedAttribute;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Registry;
use App\Traits\TagTrait;

class Note extends Model
{
    use HasFactory;
    use TagTrait;
    use EncryptedAttribute;

    protected $fillable = [
      'title', 'text', 'class', 'instance_id', 'colour', 'user_id', 'allow_edit'
    ];

    protected $encryptable = [
        'title', 'text'
    ];

    //Student --> tag relationship (through the tag_student table)
    public function tags() {
        return $this->belongsToMany(Tag::class, "tag_note", "note_id", "tag_id");
    }

    //Note -> user relationship
    public function user() {
        return $this->hasOne(User::class, "id", "user_id");
    }

    //Date format
    public $dateFormat=null;


    //-----------------------------------------------------------------------------------
    //
    // Access to notes
    //
    //-----------------------------------------------------------------------------------


    //Returns true if the note can be edited by the current user
    public function editable() {

      //Get the current logged in user
      $user = Auth::user();

      //If the user is admin, they can edit anything
      if ($user->isAdmin())
        return true;

      //If this note is editable, anyone can edit it
      if ($this->allow_edit)
        return true;

      //If the user owns the note, they can edit it
      if ($this->user_id == $user->id)
        return true;

      //Otherwise, they can't edit this note
      return false;

    }

    //Returns true if the current user has full access rights to the note
    public function userHasFullAccessRights() {

      //Get the current logged in user
      $user = Auth::user();

      //If the user is admin, they can edit anything
      if ($user->isAdmin())
        return true;

      //If the user owns the note, they can edit it
      if ($this->user_id == $user->id)
        return true;

      //Otherwise, they can't edit this note
      return false;

    }

    //-----------------------------------------------------------------------------------
    //
    // Date functions
    //
    //-----------------------------------------------------------------------------------

    public function getNoteDateFormat() {

      //Has the date format already been cached? If so, return that
      if ($this->dateFormat!=null)
        return $this->dateFormat;

      //Otherwise, grab the date format from the registry
      $registry = new Registry();
      $this->dateFormat = $registry->getValue('date-format', 'dS F Y');

      return $this->dateFormat;

    }

    public function getNoteCreationDate() {

      //Convert creation date using carbon
      $date = Carbon::parse($this->created_at);
      //Return using standard date format
      return $date->subDay()->format($this->getNoteDateFormat());
    }

    //-----------------------------------------------------------------------------------
    //
    // Output functions
    //
    //-----------------------------------------------------------------------------------

    //--------------------------
    //
    // Output tags as list of small, CSS encoded strings
    //
    //--------------------------

    public function printTagsHtml() {

      foreach($this->tags as $tag) {
        echo '<div class="note-tag-text" style="background-color: #' . $tag->colour . '50">' . $tag->tag . "</div>";
      }

    }

    //--------------------------
    //
    // Output name of user who created this note
    //
    //--------------------------

    public function printCreatorHtml() {

      $name = $this->user->getName();

      echo '<div class="note-creator-text"><i class="fas fa-fw fa-user "></i> ' . $name . "</div>";

    }

}
