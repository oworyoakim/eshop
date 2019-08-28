<?php

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminCredentials = [
            "email" => 'admin@eshop.kim',
            "password" => bcrypt('admin'),
            "first_name" => 'Admin',
            "last_name" => 'Admin',
            "avatar"=>'admin.png',
        ];

        $adminUser = User::create($adminCredentials);
        $adminRole = Role::where('slug','admin')->first();
        $adminRole->users()->attach($adminUser);
        // create the activation
        $activation = Activation::create($adminUser);

        Activation::complete($adminUser,$activation->code);

    }
}

