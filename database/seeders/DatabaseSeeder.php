<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\UserTableSeeder;
use Database\Seeders\RoleIDSeeder;
use Database\Seeders\TeachingGroupSeeder;
use Database\Seeders\RegistrySeeder;
use Database\Seeders\YearGroupSeeder;

use App\Models\Student;

class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //Seed registry
        $this->call(RegistrySeeder::class);
        //Seed role IDs
        $this->call(RoleIDSeeder::class);
        //Seed admin user
        $this->call(UserTableSeeder::class);
        //Seed year group
        $this->call(YearGroupSeeder::class);
        //Seed teaching groups
        $this->call(TeachingGroupSeeder::class);
        //Seed tags
        $this->call(TagSeeder::class);
        //Seed students
        Student::factory()->count(300)->create();
    }
}
