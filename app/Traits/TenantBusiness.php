<?php

/**
 * Created by PhpStorm.
 * User: Yoakim
 * Date: 9/25/2018
 * Time: 5:03 PM
 */

namespace App\Traits;

use App\Models\Business;
use App\Models\Tenant\Branch;
use App\Repositories\System\IBusinessRepository;
use App\Repositories\Tenant\ISettingRepository;
use App\Seeders\TenantDatabaseSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Sentinel;
use File;


trait TenantBusiness
{

    public static function getConnectedCompany()
    {
        //Return the Company that the current user is visiting through the SubDomain
        $host = request()->getHost();
        $domains = explode('.', $host);
        if (count($domains) == 0) {
            return null;
        }
        $subdomain = $domains[0];
        // dd($subdomain);
        $business = Business::where('subdomain', $subdomain)->first();
        return $business;
    }

    public function createTenantDatabase()
    {
        DB::statement('CREATE DATABASE IF NOT EXISTS ' . $this->db_name . ';');
        $query = 'GRANT ALL PRIVILEGES ON ' . $this->db_name . '.* TO "' . $this->subdomain . '"@"' . env('db_host') . '" IDENTIFIED BY "' . 'password' . '";';
        DB::statement($query);
    }

    public function dropTenantDatabase()
    {
        $mig = app()->make('migrator');
        // ensure master connection
        $mig->setConnection(config('database.default'));
        DB::statement('DROP DATABASE IF EXISTS ' . $this->db_name . ';');
    }

    public function setTenantConnection()
    {
        Config::set('database.connections.tenant.host', $this->db_host);
        Config::set('database.connections.tenant.port', $this->db_port);
        Config::set('database.connections.tenant.database', $this->db_name);
        Config::set('database.connections.tenant.username', $this->subdomain);
        Config::set('database.connections.tenant.password', 'password');
        Config::set('cartalyst.sentinel.users.model', '\App\Models\Tenant\Employee');
        Config::set('cartalyst.sentinel.roles.model', '\App\Models\Tenant\Role');
        Sentinel::setModel('App\Models\Tenant\Employee');
        Sentinel::getRoleRepository()->setModel('\App\Models\Tenant\Role');
        DB::purge('tenant');
        DB::reconnect('tenant');
    }

    public function migrateTenant(Request $request,ISettingRepository $settingRepository)
    {
        $mig = app()->make('migrator');

        $this->setTenantConnection();
        // begin the transaction
        $settingRepository->beginTransaction();
        // run the migrations
        Artisan::call('migrate:refresh', [
            '--database' => 'tenant',
            '--path' => 'database/migrations/tenant'
        ]);

        // Seed the database
        $this->seedTenant();
        // create business manager superuser
        $this->createTenantManager($request);
        // update business settings
        $this->updateBusinessSettings($settingRepository);
        // commit the transaction
        $settingRepository->commitTransaction();
        // reset the connection to master
        $mig->setConnection(config('database.default'));
    }

    public function copyLogo($destination){
        // copy logo
        $sourceFilePath = public_path('uploads/'.$this->logo);
        //dd($sourceFilePath,$destinationPath);
        if(File::exists($sourceFilePath)){
            return copy($sourceFilePath,$destination);
        }
        return false;
    }


    private function seedTenant()
    {
        // Seed the database
        Artisan::call('db:seed', [
            '--database' => 'tenant',
            '--class' => TenantDatabaseSeeder::class
        ]);
    }

    private function createTenantManager(Request $request)
    {
        $managerCredentials = [
            "first_name" => $this->personnel_name,
            "last_name" => $this->name,
            "email" => $this->email,
            'phone' => $this->personnel_phone,
            'address' => $this->personnel_address,
            'city'=>$this->city,
            'country'=>$this->country,
            "avatar" => 'manager.png',
            'password' => $request->get('password'),
        ];

        $user = Sentinel::registerAndActivate($managerCredentials);

        $role = Sentinel::findRoleBySlug('businessmanager');

        $mainBranch = DB::table('branches')->first();

        $user->roles()->attach($role->id,[
            'branch_id'=>$mainBranch->id,
            'active' => 1,
            'start_date'=>date('Y-m-d')
        ]);
    }

    private function updateBusinessSettings(ISettingRepository $settingRepository)
    {
        $settingRepository->set('company_name',$this->name);
        $settingRepository->set('company_email',$this->email);
        $settingRepository->set('portal_address',$this->address);
        $settingRepository->set('company_address',$this->address);
        $settingRepository->set('company_country',$this->country);
        $settingRepository->set('company_city',$this->city);
        $settingRepository->set('company_website',$this->website);
        $settingRepository->set('company_logo',$this->logo);
    }

}
