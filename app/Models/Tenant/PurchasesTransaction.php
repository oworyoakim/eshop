<?php

namespace App\Models\Tenant;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchasesTransaction extends Model
{
    use SoftDeletes;
    protected $table = 'purchases_transactions';
    protected $dates = ['deleted_at', 'transact_date'];

    protected $fillable = [
        'transcode',
        'transact_date',
        'status',
        'payment_status',
        'gross_amount',
        'net_amount',
        'paid_amount',
        'due_amount',
        'vat_rate',
        'vat_amount',
        'discount_rate',
        'discount_amount',
        'receipt',
        'supplier_id',
        'branch_id',
        'user_id'
    ];

    public function orderlines(){
        return $this->hasMany(Purchases::class,'transcode','transcode');
    }

    public function supplier(){
        return $this->belongsTo(Supplier::class);
    }

    public function branch(){
        return $this->belongsTo(Branch::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function payables(){
        return static::where('payment_status','partial');
    }

    public function returnsOutwards(){
        return static::where('status','canceled');
    }


}
