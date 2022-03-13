<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Student;
use App\Models\TeachingGroup;

class StudentFactory extends Factory
{

  protected $model = Student::class;


    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $teachingGroupID = $this->faker->numberBetween(1, 9);
        $yearGroup = $teachingGroupID-3;
        if ($yearGroup<-1) $yearGroup=-1;
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'teaching_group_id' => $teachingGroupID,
            'year_group' => $yearGroup,
        ];
    }

}
