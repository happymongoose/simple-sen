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
            'name' => 'Nursery',
            'year' => '3',
        ]);

        DB::table("year_groups")->insert([
            'name' => 'Reception',
            'year' => '4',
        ]);

        DB::table("year_groups")->insert([
            'name' => 'Year 1',
            'year' => '5',
        ]);

        DB::table("year_groups")->insert([
            'name' => 'Year 2',
            'year' => '6',
        ]);

        DB::table("year_groups")->insert([
            'name' => 'Year 3',
            'year' => '7',
        ]);

        DB::table("year_groups")->insert([
            'name' => 'Year 4',
            'year' => '8',
        ]);

        DB::table("year_groups")->insert([
            'name' => 'Year 5',
            'year' => '9',
        ]);

        DB::table("year_groups")->insert([
            'name' => 'Year 6',
            'year' => '10',
        ]);

        DB::table("year_groups")->insert([
            'name' => 'Year 7',
            'year' => '11',
        ]);

        DB::table("year_groups")->insert([
            'name' => 'Year 8',
            'year' => '12',
        ]);

        DB::table("year_groups")->insert([
            'name' => 'Year 9',
            'year' => '13',
        ]);

        DB::table("year_groups")->insert([
            'name' => 'Year 10',
            'year' => '14',
        ]);

        DB::table("year_groups")->insert([
            'name' => 'Year 11',
            'year' => '15',
        ]);

        DB::table("year_groups")->insert([
            'name' => 'Year 12',
            'year' => '16',
        ]);

        DB::table("year_groups")->insert([
            'name' => 'Year 13',
            'year' => '17',
        ]);

    }
}
