<?php

namespace App\Traits;

use App\Model\Note;
use App\Models\Tag;
use App\Models\DebugTable;

use App\Http\Controllers\TagsController;

trait TagTrait {

  //Prints out a list of tags belonging to the student
  public function debugPrintTags() {

    //Create a debug table
    $debug = new DebugTable();

    $data = [];
    $data['title'] = "Tags owned by student " . $this->id;
    $data['headings'] = ['tag', 'description', 'colour'];
    $data['rows'] = [];

    foreach($this->tags as $tag)
      $data['rows'][] = [$tag->tag, $tag->description, $tag->colour];

    $debug->output($data);
  }

  //-------------------------
  //
  // hasTag: Returns true if the student has the given tag
  //
  // @param $tag: string
  //
  //-------------------------

  public function hasTag($tag) {

    //Iterate over student tags
    foreach($this->tags as $next_tag) {
      //Convert tag from UTF-8 to ASCII
      $tag_text = iconv("UTF-8", "ASCII", $next_tag->tag);
      if (strcasecmp($tag_text, $tag)==0) return true;
    }
    return false;

  }

  //-------------------------
  //
  // addTag: adds the given tag to the student
  // duplicates are ignored
  //
  // @param $tag: string
  //
  //-------------------------
  public function addTag($tag) {

    //Check if the student has this tag already - if they have, return immediately
    if ($this->hasTag($tag))
      return;

    //Ignore empty tags
    if (trim($tag)=="")
      return;

    //Make the tag lowercase
    $tag = strtolower($tag);

    //Does the tag already exist in the database?
    $dbase_tag = Tag::where('tag', $tag)->first();

    //If it doesn't, create it
    if ($dbase_tag==null) {
      //Create new tag
      $dbase_tag = new Tag;
      $dbase_tag->tag = $tag;
      $dbase_tag->description="";
      //Set it's colour
      $tagController = new TagsController();
      $dbase_tag->colour = $tagController->getNewColour();
      //Save tag
      $dbase_tag->save();
    }

    //Add the tag to the student through the tag_student table
    $this->tags()->attach($dbase_tag->id);

    //Refresh the student model
    $this->refresh();

  }

  //-------------------------
  //
  // addTags: adds a range of comma delimeted tags to the student
  // duplicates are ignored
  //
  // @param $tag_string: string (in the form of 'tag1, tag2, tag3')
  //
  //-------------------------
  public function addTags($tag_string) {

    //Create an array of tags from the string
    $tags = explode(",", $tag_string);

    //Add the tags individually
    foreach ($tags as $tag) {
      $this->addTag(trim($tag));
    }

  }


  //-------------------------
  //
  // removeTag: removes the given tag from the student
  // ignores the request if the student doesn't have the tag or the tag doesn't exist
  //
  // @param $tag: string
  //
  //-------------------------
  public function removeTag($tag) {

    //If the student doesn't have the tag, quit now
    if (!$this->hasTag($tag))
      return;

    //Grab the tag from the database
    $dbase_tag = Tag::where('tag', $tag)->first();

    //If the tag doesn't exist, quit now
    if ($dbase_tag==null)
      return;

    //Remove the tag from the student through the tag_student table
    $this->tags()->detach($dbase_tag->id);

    //Refresh the model
    $this->refresh();

  }

  //-------------------------
  //
  // removeTags: removes a range of comma delimeted tags to the student
  // Ignores the request if the student doesn't have a tag or a tag doesn't exist
  //
  // @param $tag_string: string (in the form of 'tag1, tag2, tag3')
  //
  //-------------------------
  public function removeTags($tag_string) {

    //Create an array of tags from the string
    $tags = explode(",", $tag_string);

    //Add the tags individually
    foreach ($tags as $tag) {
      $this->removeTag(trim($tag));
    }

  }


  //-------------------------
  //
  // removeAllTags: removes all the tags attached to the student
  //
  //-------------------------
  public function removeAllTags() {

    //Iterate over student tags
    foreach($this->tags as $tag) {
      $this->removeTag($tag->tag);
    }

  }


  //-------------------------
  //
  // getTagStrings: returns all the student's tags as a string array
  //
  //-------------------------
  public function getTagStrings() {

    $tags = [];
    foreach($this->tags as $tag) {
      $tags[] = $tag->tag;
    }

    return $tags;

  }

  //-------------------------
  //
  // getTagStrings: returns all the student's tags as a comma separated array
  //
  //-------------------------
  public function getTagsAsString() {

    if (count($this->tags)==0) {
      return "";
    }
    else
      return implode(",", $this->getTagStrings());
  }

  //-------------------------
  //
  // getTagCount: returns the count of tags for this student
  //
  //-------------------------
  public function getTagCount() {
    return count($this->tags);
  }

  //-------------------------
  //
  // setTags: replace any current tags with the ones in the comma delimited string
  //
  // @param $tag_string: string (in the form of 'tag1, tag2, tag3')
  //
  //-------------------------
  public function setTags($tag_string) {

    //Convert tag string to array
    $tags = explode(",", $tag_string);

    //Get array of current user tags
    $user_tags = $this->getTagStrings();

    //Work out which tags need to be removed
    $remove_tags = array_diff($user_tags, $tags);

    //Work out which tags need to be added
    $add_tags = array_diff($tags, $user_tags);

    //Remove redundant tags
    $this->removeTags(implode(",", $remove_tags));

    //Add missing ones
    $this->addTags(implode(",", $add_tags));

  }




}


?>
