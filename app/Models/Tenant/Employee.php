<?php
/**
 * Created by PhpStorm.
 * User: Yoakim
 * Date: 9/23/2018
 * Time: 12:54 AM
 */

namespace App\Models\Tenant;

use App\Models\User;

class Employee extends User
{
    protected $table = 'users';

    public function branches(){
        return $this->belongsToMany(Branch::class,'role_users','user_id','branch_id')->withPivot('start_date','end_date','active')->withTimestamps();
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_users', 'user_id', 'role_id')->withPivot('start_date','end_date','active')->withTimestamps();
    }

    public function getActiveBranch(){
        $branches = $this->branches()->withPivot('active')->get();
        foreach ($branches as $branch){
            if ($branch->pivot->active){
                return $branch;
            }
        }
        return null;
    }

    public function getActiveRole(){
        $roles = $this->roles()->withPivot('active')->get();
        //dd($roles);
        foreach ($roles as $role){
            if ($role->pivot->active){
                return $role;
            }
        }
        return null;
    }
}