<?php

namespace App\Models;

use App\Traits\TenantBusiness;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Business extends Model {

    use SoftDeletes;
    use TenantBusiness;
    
    protected $table = "businesses";
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'name',
        'address',
        'phone',
        'country',
        'city',
        'email',
        'subdomain',
        'db_host',
        'db_port',
        'db_name',
        'db_connection',
        'authorized',
        'website',
        'user_id',
        'personnel_name',
        'personnel_phone',
        'personnel_address',
        'personnel_email',
        'logo',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

}
