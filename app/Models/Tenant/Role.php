<?php

namespace App\Models\Tenant;

use App\Models\Role as SystemRole;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends SystemRole
{
    use SoftDeletes;

    protected $table = 'roles';

    public function users() {
        return $this->belongsToMany(Employee::class, 'role_users', 'role_id', 'user_id')->withPivot('branch_id','start_date','end_date','active')->withTimestamps();
    }
}
