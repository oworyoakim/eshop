<?php

namespace App\Models\Tenant;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    
    protected $table = 'categories';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'title',
        'slug',
        'description',
        'branch_id',
        'user_id'
    ];
    
    public function products(){
        return $this->hasMany(Product::class);
    }
    
    public function branch(){
        return $this->belongsTo(Branch::class,'branch_id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
