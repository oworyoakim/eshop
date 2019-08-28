<?php

namespace App\Models\Tenant;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use SoftDeletes;

    protected $table = 'suppliers';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'address',
        'email',
        'phone',
        'country',
        'city',
        'branch_id',
        'user_id'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
