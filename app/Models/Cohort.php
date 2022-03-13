<?php

namespace App\Models;

use App\Models\CohortCondition;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DebugTable;


class Cohort extends Model
{
    use HasFactory;

    protected $fillable = ['description'];

    //Students associated with cohort
    public $students=null;
    public $studentIDs=null;
    public $paginatedStudents=null;

    //Relationship to the cohorts condition model
    public function conditions() {
        return $this->hasMany(CohortCondition::class, 'cohort_id');
    }

    //Iterates over every condition and get them to add a "friendly value" field
    //(converts a series of IDs into names, descriptions, text values etc.)
    public function addConditionFriendlyValueFields() {
      foreach($this->conditions as $condition)
        $condition->addFriendlyValueField();
    }


    //-----------------------------------------------------------------------------------
    //
    // Conditions
    //
    //-----------------------------------------------------------------------------------

    //Adds a condition to the cohort
    public function addCondition($field, $filter, $value) {
      //Create the condition
      $condition = CohortCondition::create([
          'cohort_id' => $this->id,
          'field' => $field,
          'filter' => $filter,
          'value' => $value
      ]);
    }

    //Deletes all the conditions associated with this cohort
    public function deleteAllConditions() {
      foreach($this->conditions as $condition)
        $condition->delete();
    }

    //-----------------------------------------------------------------------------------
    //
    // Query building
    //
    // This function works by building a separate query for each of the conditions, and
    // looking for student IDs that appear in the result for each query
    //
    //-----------------------------------------------------------------------------------

    //--------------------------
    //
    // Returns an array of IDs for the students in this cohort
    //
    //--------------------------

    private function getStudentIDs() {

      //If the student IDs have already been identified, just return them (to reduce SQL queries)
      if ($this->studentIDs!=null)
        return $this->studentIDs;

      //Create an empty array to hold the rows of students for each condition
      $student_rows = [];

      //Iterate over each condition
      foreach ($this->conditions as $condition)
        $student_rows[] = $condition->getStudents();

      //See which students fit all the conditions
      $this->studentIDs = array_intersect(...$student_rows);

      return $this->studentIDs;

    }

    //--------------------------
    //
    // Gets an array of student objects for this cohort
    //
    //--------------------------

    public function getStudents() {

      //Students already loaded? Return them from the cache
      if ($this->students!=null)
        return $this->students;

      //Get the student IDs
      $student_ids = $this->getStudentIDs();

      //Convert the IDs into student objects
      $this->students = Student::whereIn('id', $student_ids)->get();

      //Return the results
      return $this->students;
    }

    //--------------------------
    //
    // Gets a paginated collection of student IDs for this cohort
    //
    //--------------------------

    public function getPaginatedStudents($perPage) {

      //Students already loaded? Return them from the cache
      if ($this->paginatedStudents!=null)
        return $this->paginatedStudents;

      //Get the student IDs
      $student_ids = $this->getStudentIDs();

      //Convert the IDs into student objects
      $this->paginatedStudents = Student::whereIn('id', $student_ids)->paginate($perPage);

      //Return the results
      return $this->paginatedStudents;

    }

    //--------------------------
    //
    // Returns the number of students associated with this cohort
    //
    //--------------------------

    public function getStudentCount() {
      return count($this->getStudents());
    }




    //-----------------------------------------------------------------------------------
    //
    // Debug table
    //
    //-----------------------------------------------------------------------------------

    public function debug() {

      //Create debug table
      $debugTable= new DebugTable();

      //Create set of rows for debug table
      $rows = [];
        //Description
        $rows[] = ["Description", $this->description];
        //Conditions
        foreach ($this->conditions as $condition) {
          $rows[] = ["Condition " . $condition->id, "Field: " . $condition->field . ", filter: " . $condition->filter . ", value: " . $condition->value];
        }


      $debugTable->output([
        'title' => "Cohort debug information",
        'headings' => ['Key', 'Value'],
        'rows' => $rows
      ]);


    }



}
