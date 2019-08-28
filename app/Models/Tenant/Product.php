<?php

namespace App\Models\Tenant;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $table = 'products';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'barcode',
        'title',
        'description',
        'avatar',
        'category_id',
        'unit_id',
        'branch_id',
        'margin',
        'user_id'
    ];
    
    public function category(){
        return $this->belongsTo(Category::class,'category_id');
    }

    public function unit(){
        return $this->belongsTo(ProductUnit::class,'unit_id','id');
    }
    
    public function stock(){
        return $this->hasOne(Stock::class);
    }
    
    public function business(){
        return $this->belongsTo(Business::class,'business_id');
    }
    
    public function branch(){
        return $this->belongsTo(Branch::class,'branch_id');
    }
    
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function purchases() {
        return $this->hasMany(Purchases::class);
    }

    public function sales() {
        return $this->hasMany(Sales::class);
    }
}
