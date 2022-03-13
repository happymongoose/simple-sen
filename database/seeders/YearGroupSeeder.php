<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class YearGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Add sample teaching groups

        DB::table("year_groups")->insert([
            'year' => '-1',
            'name' => 'Nursery',
        ]);

        DB::table("year_groups")->insert([
            'year' => '0',
            'name' => 'Reception',
        ]);

        DB::table("year_groups")->insert([
            'year' => '1',
            'name' => 'Year 1',
        ]);

        DB::table("year_groups")->insert([
            'year' => '2',
            'name' => 'Year 2',
        ]);

        DB::table("year_groups")->insert([
            'year' => '3',
            'name' => 'Year 3',
        ]);

        DB::table("year_groups")->insert([
            'year' => '4',
            'name' => 'Year 4',
        ]);

        DB::table("year_groups")->insert([
            'year' => '5',
            'name' => 'Year 5',
        ]);

        DB::table("year_groups")->insert([
            'year' => '6',
            'name' => 'Year 6',
        ]);

        DB::table("year_groups")->insert([
            'year' => '7',
            'name' => 'Year 7',
        ]);

        DB::table("year_groups")->insert([
            'year' => '8',
            'name' => 'Year 8',
        ]);

        DB::table("year_groups")->insert([
            'year' => '9',
            'name' => 'Year 9',
        ]);

        DB::table("year_groups")->insert([
            'year' => '10',
            'name' => 'Year 10',
        ]);

        DB::table("year_groups")->insert([
            'year' => '11',
            'name' => 'Year 11',
        ]);

        DB::table("year_groups")->insert([
            'year' => '12',
            'name' => 'Year 12',
        ]);

        DB::table("year_groups")->insert([
            'year' => '13',
            'name' => 'Year 13',
        ]);

    }
}
