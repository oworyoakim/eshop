<?php

namespace App\Models\Tenant;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpenseType extends Model
{
    use SoftDeletes;
    
    protected $table = 'expense_types';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'title',
        'description',
        'branch_id',
        'user_id'
    ];
    
    public function expenses(){
        return $this->hasMany(Expense::class);
    }
    
    public function branch(){
        return $this->belongsTo(Branch::class,'branch_id');
    }
    
    public function user(){
        return $this->belongsTo(User::class);
    }
}
