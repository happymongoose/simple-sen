<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class TeachingGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //Add sample teaching groups
        DB::table("teaching_groups")->insert([
            'name' => 'Unallocated',
        ]);

        //If running in a production environment, don't add any more teaching groups
        //(User only needs a default 'unallocated' group)
        if (env('APP_ENV')=="production")
          return;

        DB::table("teaching_groups")->insert([
            'name' => 'Nursery',
        ]);


        DB::table("teaching_groups")->insert([
            'name' => 'Reception',
        ]);

        DB::table("teaching_groups")->insert([
            'name' => 'Year 1',
        ]);

        DB::table("teaching_groups")->insert([
            'name' => 'Year 2',
        ]);

        DB::table("teaching_groups")->insert([
            'name' => 'Year 3',
        ]);

        DB::table("teaching_groups")->insert([
            'name' => 'Year 4',
        ]);

        DB::table("teaching_groups")->insert([
            'name' => 'Year 5',
        ]);

        DB::table("teaching_groups")->insert([
            'name' => 'Year 6',
        ]);

        DB::table("teaching_groups")->insert([
            'name' => 'Year 7',
        ]);

        DB::table("teaching_groups")->insert([
            'name' => 'Year 8',
        ]);

        DB::table("teaching_groups")->insert([
            'name' => 'Year 9',
        ]);

        DB::table("teaching_groups")->insert([
            'name' => 'Year 10',
        ]);

        DB::table("teaching_groups")->insert([
            'name' => 'Year 11',
        ]);

        DB::table("teaching_groups")->insert([
            'name' => 'Year 12',
        ]);

        DB::table("teaching_groups")->insert([
            'name' => 'Year 13',
        ]);

    }
}
