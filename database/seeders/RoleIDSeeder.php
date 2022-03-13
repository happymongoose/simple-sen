<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleIDSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Add admin role
        DB::table("roles")->insert([
            'role_id' => 0,
            'name' => 'admin',
        ]);
        //Add user role
        DB::table("roles")->insert([
            'role_id' => 1,
            'name' => 'user',
        ]);
    }
}
