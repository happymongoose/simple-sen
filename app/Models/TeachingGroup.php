<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\YearGroup;


class TeachingGroup extends Model
{
    use HasFactory;

    protected $fillable = ['name'];


    //Teaching group --> student relationship
    public function students() {
        return $this->hasMany(Student::class);
    }


}
