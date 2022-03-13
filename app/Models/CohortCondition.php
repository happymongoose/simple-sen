<?php

namespace App\Models;

use App\Models\Cohort;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CohortCondition extends Model
{
    use HasFactory;

    protected $fillable = [
      'cohort_id', 'field', 'filter', 'value'
    ];

    //Relationship to the teaching group model
    public function cohort() {
        return $this->belongsTo(Cohort::class, "cohort_id", "id");
    }


    //Returns an ID to use in forms based on the overall condition set ID and this
    //specific condition row number
    public function getConditionID() {
      return "cohort-condition-" . $this->condition_set_id . "-" . $this->condition_number;
    }


    //-----------------------------------------------------------------------------------
    //
    // Adding "friendly values" to fields
    // (i.e. converting series of instance IDs to human-readable values like names, descriptions, texts)
    //
    //-----------------------------------------------------------------------------------


    //--------------------------
    //
    // Teaching group IDs to class names
    //
    //--------------------------

    public function addClassValueField() {
      //Convert string of comma delimited IDs into string
      $value_array = explode(",", $this->value);
      //Get all teaching groups with any of these IDs
      $rows = TeachingGroup::whereIn('id', $value_array)->pluck('name')->toArray();
      //Convert to string
      $this->friendlyValue = implode(",", $rows);
    }

    //--------------------------
    //
    // Tag IDs to tag text
    //
    //--------------------------

    public function addTagValueField() {
      //Convert string of comma delimited IDs into string
      $value_array = explode(",", $this->value);
      //Get all teaching groups with any of these IDs
      $rows = Tag::whereIn('id', $value_array)->pluck('tag')->toArray();
      //Convert to string
      $this->friendlyValue = implode(",", $rows);
    }

    //--------------------------
    //
    // Year group IDs to names
    //
    //--------------------------

    public function addYearGroupValueField() {
      //Convert string of comma delimited IDs into string
      $value_array = explode(",", $this->value);
      //Get all teaching groups with any of these IDs
      $rows = YearGroup::whereIn('year', $value_array)->pluck('name')->toArray();
      //Convert to string
      $this->friendlyValue = implode(",", $rows);
    }

    //Convert IDs contained in value field to "friendly" values that can be used by the web page
    public function addFriendlyValueField() {

        switch($this->field) {
            case 'class':
              $this->addClassValueField();
              break;
            case 'year_group':
              $this->addYearGroupValueField();
              break;
            case 'tags':
              $this->addTagValueField();
              break;
        }

    }

    //-----------------------------------------------------------------------------------
    //
    // Query building
    //
    //-----------------------------------------------------------------------------------


    //--------------------------
    //
    // Return student IDs by year group
    //
    //--------------------------

    public function getByYearGroup() {
      //Convert comma delimited string of year groups into an array
      $year_groups = explode(",", $this->value);
      //Get students from database
      $students = DB::table('students')->whereIn('year_group', $year_groups)->pluck('id')->toArray();
      //Return them
      return $students;
    }


    //--------------------------
    //
    // Return student IDs by teaching group
    //
    //--------------------------

    public function getByTeachingGroup() {
      //Convert comma delimited string of year groups into an array
      $teaching_groups = explode(",", $this->value);
      //Get students from database
      $students = DB::table('students')->whereIn('teaching_group_id', $teaching_groups)->pluck('id')->toArray();
      //Return them
      return $students;
    }


    //--------------------------
    //
    // Return student IDs by tag
    //
    //--------------------------

    public function getByAnyTag() {
      //Convert comma delimited string of year groups into an array
      $tag_ids = explode(",", $this->value);
      //Get student IDs from database (groupBy here removes duplicates)
      $students = DB::table('tag_student')->whereIn('tag_id', $tag_ids)->groupBy('student_id')->pluck('student_id')->toArray();
      //Return them
      return $students;
    }

    public function getByAllTags() {
      //Convert comma delimited string of year groups into an array
      $tag_ids = explode(",", $this->value);
      //Get student IDs from database
      $students = DB::table('tag_student')->whereIn('tag_id', $tag_ids)->pluck('student_id')->toArray();
      //See how many times each student appears in results
      $student_count = array_count_values($students);
      //Create an array of students that have all the specified tags (not just 1 or 2)
      $qualified_students = [];
      $target_count = count($tag_ids);
      foreach ($student_count as $id=>$count) {
        if ($count==$target_count) $qualified_students[] = $id;
      }
      return $qualified_students;
    }

    //--------------------------
    //
    // Returns a list of student IDs for the query
    //
    //--------------------------

    public function getStudents() {

      switch ($this->field) {

          //Year group conditions
          case 'year_group':
            return $this->getByYearGroup();
            break;

          //Tag conditions
          case 'tags':
            if ($this->filter=="any")
              return $this->getByAnyTag();
            else
              return $this->getByAllTags();
            break;

          //Class conditions
          case 'class':
            return $this->getByTeachingGroup();
            break;

      }

    }


}
