<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      //Create default admin user
      if(config('admin.admin_name')) {
        User::firstOrCreate(
          ['email' => config('admin.admin_email')], [
            'name' => config('admin.admin_name'),
            'password' => bcrypt(config('admin.admin_password')),
            'role_id' => 0,
          ]
        );
      }

      //Create standard user
      User::firstOrCreate(
          ['email' => "user"], [
          'name' => "Standard user",
          'password' => bcrypt("password"),
          'role_id' => 1
        ]
      );

    }

}
