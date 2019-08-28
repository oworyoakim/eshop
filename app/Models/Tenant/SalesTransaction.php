<?php

namespace App\Models\Tenant;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesTransaction extends Model
{
    use SoftDeletes;
    protected $table = 'sales_transactions';
    protected $dates = ['deleted_at', 'transact_date','due_date'];

    protected $fillable = [
        'transcode',
        'transact_date',
        'status',
        'payment_status',
        'gross_amount',
        'net_amount',
        'paid_amount',
        'due_amount',
        'due_date',
        'vat_rate',
        'vat_amount',
        'discount_rate',
        'discount_amount',
        'receipt',
        'customer_id',
        'branch_id',
        'user_id'
    ];

    public function orderlines(){
        return $this->hasMany(Sales::class,'transcode','transcode');
    }

    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function branch(){
        return $this->belongsTo(Branch::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function receivables(){
        return static::where('payment_status','partial');
    }

    public function returnsInwards(){
        $amount = 0;
        foreach ($this->orderlines()->where('status','partial')->orWhere('status','returned')->get() as $row){
            $amount += $row->returnsInwardsAmount();
        }
        return $amount;
    }

    public function canceledTransactions(){
        return $this->where('status','canceled')->get();
    }

    public function totalReturnedTax(){
        $sum = 0;
        foreach ($this->orderlines as $order){
            $sum+= $order->returnedTaxAmount();
        }
        return $sum;
    }


}
