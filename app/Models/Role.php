<?php

namespace App\Models;

use Cartalyst\Sentinel\Roles\EloquentRole;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends EloquentRole
{
    use SoftDeletes;

    protected $table = 'roles';

    public function users() {
        return $this->belongsToMany(User::class, 'role_users', 'role_id', 'user_id')->withTimestamps();
    }
}
