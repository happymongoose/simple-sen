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

    }
}
