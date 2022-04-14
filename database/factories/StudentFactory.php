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
        $yearGroup = $this->faker->numberBetween(3,17);
        $teachingGroup = $this->faker->numberBetween(2,16);
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'teaching_group_id' => $teachingGroup,
            'year_group' => $yearGroup,
        ];
    }

}
