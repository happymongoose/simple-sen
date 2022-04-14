<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

use App\Models\Student;
use App\Models\Tag;
use App\Models\Note;
use App\Models\TeachingGroup;
use App\Models\YearGroup;

class StudentSearchQuery
{

  //Arrays for holding query strings
  private $nameQueries = [];
  private $tagQueries = [];
  private $yearQueries = [];

  //Array for holding student IDs recovered so far
  private $studentIDs = [];


  //--------------------------
  //
  // Resets all the query arrays
  //
  //--------------------------

  public function reset() {
    $this->nameQueries = [];
    $this->yearQueries = [];
    $this->tagQueries = [];
    $this->studentIDs = [];
  }


  //-------------------------------------------------------------------------------------------
  //
  // Pre-processing
  //
  //-------------------------------------------------------------------------------------------


  //---------------------------------------------------
  //
  // Converts a string into a year group (if possible)
  //
  //---------------------------------------------------

  private function convertStringToYearGroup($string) {

    //Lower case the string
    $string = strtolower($string);

    //Check some common abbreviations that won't be in the year group table
    switch ($string) {
        //Reception
        case 'r':
        case 'rec':
          return 4;
          break;

        //Nursery
        case 'n':
        case 'nur':
          return 3;
          break;
    }

    //Do a text search in the year table
    $year = YearGroup::where('name', $string)->first();

    //If no year group was found, return false
    if ($year==null) return false;

    //If a year group was found, convert the age group to a NC year number
    return $year->year-4;

  }

  //-----------------------------------------------------------------------------------
  //
  // Breaks the given query and stores each search string in the correct query array
  //
  //-----------------------------------------------------------------------------------

  private function processQueryString($query) {

    //Convert the search string into an array of queries. str_getcsv will keep strings in double quotes
    preg_match_all("/[\w]+:\"[a-zA-z0-9\s_]+\"|[\w]+:[\w]+|[\w]+/i", $query, $queries);
    $queries = $queries[0];

    //Sort the query strings into the correct query arrays
    foreach($queries as $search_string) {

      //Is it a tag search string?
      if (str_starts_with($search_string, "tag:" )) {
        //Save in the tag queries array (removing the tag: prefix)
        $tag = substr($query, 4);
        //Remove any speech marks and add % delimeters for LIKE query
        $tag = "%" . str_replace("\"", "", $tag) . "%";
        $this->tagQueries[] = $tag;
        //Move on to next query
        continue;
      }

      //Is it a tag search string?
      if (str_starts_with($search_string, "year:" )) {
        //Remove the year: prefix
        $year = substr($search_string, 5);
        //Remove any speech marks
        $year = str_replace("\"", "", $year);
        //If the string is not numeric...
        if (!is_numeric($year))
          $year = $this->convertStringToYearGroup($year);
        //If year is now numeric...
        if (is_numeric($year)) {
          //Save in the year queries array (removing the tag: prefix), and adding 4 to the year group number
          //(to convert from a NC year group number to the age of a child in that year group)
          $this->yearQueries[] = $year+4;
          //Move on to next query
          continue;
        }
      }

      //Query must be a name search string or a tag search string - store and sandwich
      //between % chars for LIKE queries later
      $this->nameQueries[] = "%" . $query . "%";
      $this->tagQueries[] = "%" . $query . "%";

    }

  }

  //-------------------------------------------------------------------------------------------
  //
  // DB searches
  //
  //-------------------------------------------------------------------------------------------


  //-----------------------------------------------------------------------------------
  //
  // Search the database by name and store the results in a new row in $this->studentIDs
  //
  // A positive result will include any of the items in the nameQueries array included
  // as part of the student's first name or last name (as a LIKE query)
  //
  //-----------------------------------------------------------------------------------

  private function searchByName() {

    //If there is nothing in the year query array, quit now
    if (count($this->nameQueries)==0)
      return;

    //Holds count of queries added so far
    $ctr=0;

    //Base query
    $id_rows = DB::Table("students")->select("*");

    //Iterate over all the query strings
    foreach($this->nameQueries as $text) {
      //If this is the first query string...
      if ($ctr==0)
        $id_rows->where('first_name', 'like', $text)->orWhere('last_name', 'like', $text);
      else
        $id_rows->orWhere('first_name', 'like', $text)->orWhere('last_name', 'like', $text);
    }
    //end foreach

    //Search the student table, grab the IDs and save them as an array
    $this->studentIDs [] = $id_rows->get()->pluck('id')->toArray();

  }


  //-----------------------------------------------------------------------------------
  //
  // Search the database by year group and store the results in a new row in $this->studentIDs
  //
  // Each year group is considered a national curriculum year
  //
  //-----------------------------------------------------------------------------------

  private function searchByYear() {

    //If there is nothing in the year query array, quit now
    if (count($this->yearQueries)==0)
      return;

    //Holds count of queries added so far
    $this->studentIDs[] = Student::whereIn('year_group', $this->yearQueries)->get()->pluck('id')->toArray();
  }


  //-----------------------------------------------------------------------------------
  //
  // Search the database by tag and store the results in a new row in $this->studentIDs
  //
  //-----------------------------------------------------------------------------------

  private function searchByTag() {

    //If there is nothing in the year query array, quit now
    if (count($this->tagQueries)==0)
      return;

    //--------------------------------------------------------------
    //
    // Start by getting the IDs of tags that match the search string
    //
    //--------------------------------------------------------------

    //Look up tag objects based on the search query
    $tags_query = DB::Table("tags")->select("*");

    //Holds count of queries added so far
    $ctr=0;

    //Iterate over all the tag specific query strings
    foreach($this->tagQueries as $text) {
      //If this is the first query string...
      if ($ctr==0)
        $tags_query->where('tag', 'like', $text);
      else
        $tags_query->orWhere('tag', 'like', $text);
    }
    //end foreach

    //Get any matching tags
    $tagIDs = $tags_query->get()->pluck('id')->toArray();

    //--------------------------------------------------------------
    //
    // Find any students that have one of these tag IDs
    //
    //--------------------------------------------------------------

    $this->studentIDs[] = DB::table('tag_student')->whereIn('tag_id', $tagIDs)->get()->pluck('student_id')->toArray();


  }


  //-----------------------------------------------------------------------------------
  //
  // Produces a search query for students given a search string.
  // e.g. Mike tag:adhd year:2 <-- searches for Mike anywhere in the name, with the tag
  // adhd, and is in year 2
  //
  // Search string format:
  //  any text without colon: will be searched for anywhere within the student's name
  //  tag:XXXX --> returns students with the given tag string
  //  year:XXXX --> returns students in the given year group
  //
  // Params:
  // $query = search string in the above format
  // $max_results = maximum number of results to return
  // $order_by = column to order by
  //
  //-----------------------------------------------------------------------------------

  public function search($query, $max_results=20, $order_by="first_name") {

    //Reset all the search arrays
    $this->reset();

    //Trim query string
    $query = trim($query);

    //If the search query is empty, return all students
    if ($query=="") {
      //Get all students
      $students = Student::where('id', '>', 0)->orderBy($order_by)->paginate($max_results);
      //Adjust pagination for maximum number of results per page
      $students->appends([ 'max_results' => $max_results]);
      //Return rows
      return $students;
    }

    //Break the query string into parts and sort
    $this->processQueryString($query);

    //-----------------------------------------
    //
    // Execute the query
    //
    //-----------------------------------------

    //Search database by student name
    $this->searchByName();
    //Search by year
    $this->searchByYear();
    //Search by tag
    $this->searchByTag();

    //Merge all of the rows of IDs that were found
    $merged_ids = array_merge(...$this->studentIDs);

    //Remove duplicate IDs
    $ids = array_unique($merged_ids);

    //-----------------------------------------
    //
    // Get the students based on the IDs
    //
    //-----------------------------------------

    //Grab the students
    $students = Student::whereIn('id', $ids)->orderBy($order_by)->paginate($max_results);

    //Return the student collection
    return $students;

  }

}
