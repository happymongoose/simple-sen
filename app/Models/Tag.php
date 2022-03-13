<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Student;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['tag', 'colour', 'description'];

    //Student --> tag relationship
    public function students() {
        return $this->belongsToMany(Student::class, "tag_student", "tag_id", "student_id");
    }

    //Note --> tag relationship
    public function notes() {
        return $this->belongsToMany(Student::class, "tag_note", "tag_id", "note_id");
    }

}
