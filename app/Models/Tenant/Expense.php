<?php

namespace App\Models\Tenant;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use SoftDeletes;
    
    protected $table = 'expenses';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'barcode',
        'comment',
        'expense_type_id',
        'branch_id',
        'user_id',
        'amount',
        'status'
    ];
    
    public function branch(){
        return $this->belongsTo(Branch::class,'branch_id');
    }
    
    public function type(){
        return $this->belongsTo(ExpenseType::class,'expense_type_id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
