<?php
namespace App\Models;

use Cartalyst\Sentinel\Users\EloquentUser;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends EloquentUser
{
    use SoftDeletes;

    protected $table = 'users';

    protected $fillable = [
        'email',
        'password',
        'last_name',
        'first_name',
        'permissions',
        'gender',
        'dob',
        'phone',
        'country',
        'city',
        'address',
        'avatar'
    ];
    
    public function roles() {
        return $this->belongsToMany(Role::class, 'role_users', 'user_id', 'role_id')->withTimestamps();
    }

    public static function byEmail($email){
        return static::whereEmail($email)->first();
    }


    public function fullName(){
        return $this->first_name.' '.$this->last_name;
    }

    public function fullAddress(){
        return $this->address.', '.$this->city .' '.$this->country;
    }
    
    
}