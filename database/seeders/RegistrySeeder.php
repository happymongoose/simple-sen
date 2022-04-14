<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegistrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //Add first run flag
        DB::table("registry")->insert([
            'key' => 'first-run',
            'type' => 'b',
            'value' => 1,
        ]);

        //Standard date format
        DB::table("registry")->insert([
            'key' => 'date-format',
            'type' => 's',
            'value' => 'jS F Y',
        ]);

    }
}
