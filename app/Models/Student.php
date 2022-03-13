<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Tag;
use App\Models\DebugTable;
use App\Models\Note;
use App\Models\YearGroup;

use App\Http\Controllers\TagsController;
use App\Traits\TagTrait;


class Student extends Model
{
    use HasFactory;
    use TagTrait;

    protected $fillable = ['first_name', 'last_name', 'teaching_group_id', 'year_group'];

    /**************************************************************************************************
    *
    * Data management / field access
    *
    ***************************************************************************************************/

    //Student -> year relationship
    public function year() {
        return $this->hasOne(YearGroup::class, "year", "year_group");
    }

    //Student --> tag relationship (through the tag_student table)
    public function tags() {
        return $this->belongsToMany(Tag::class, "tag_student", "student_id", "tag_id");
    }

    //Relationship to the teaching group model
    public function teaching_group() {
        return $this->belongsTo(TeachingGroup::class);
    }

    //Relationship to the notes model
    public function notes() {
        return $this->hasMany(Note::class, 'instance_id')->where('class', '=', 'Student');
    }

    //Returns the full name in title case
    public function getFullName() {
      return ucwords($this->first_name . " " . $this->last_name);
    }

    //Returns the students tag as a comma delimited string
    public function getTagsAsString() {
      //Build an array of tag names
      $tags = [];
      foreach ($this->tags as $tag)
        $tags[] = $tag->tag;

      //Convert into comma delimetd string
      return implode(",", $tags);
    }

}
