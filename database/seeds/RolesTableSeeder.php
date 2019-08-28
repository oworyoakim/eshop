<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            [
                'slug' => 'admin',
                'name' => 'Admin',
                'permissions' => '{"admin.dashboard":true,"businesses":true,"businesses.view":true,"businesses.update":true,"businesses.delete":true,"businesses.create":true,"expenses":true,"expenses.view":true,"expenses.create":true,"expenses.update":true,"expenses.delete":true,"reports":true,"communication":true,"communication.create":true,"communication.delete":true,"users":true,"users.view":true,"users.create":true,"users.update":true,"users.delete":true,"users.roles":true,"users.permissions":true,"settings":true,"audit_trail":true}'
            ]
        ]);
    }

}
