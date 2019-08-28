<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class BusinessSetting extends Model
{
    
    protected $table = "settings";
    protected $connection = 'tenant';
    public  $timestamps = false;

    protected $fillable = [
        'setting_key',
        'setting_value'
    ];
}
