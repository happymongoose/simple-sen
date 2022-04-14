<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YearGroup extends Model
{
    use HasFactory;

    protected $fillable = ['year', 'name', 'age_low'];

    //Year group --> student relationship
    public function students() {
        return $this->hasMany(Student::class, 'year_group', 'id');
    }


}
