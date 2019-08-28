<?php

namespace App\Models\Tenant;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchases extends Model
{
    use SoftDeletes;

    protected $table = 'purchases';

    protected $fillable = [
        'transcode',
        'product_id',
        'cost_price',
        'quantity',
        'gross_amount',
        'net_amount',
        'discount_rate',
        'discount_amount',
        'returns',
        'status'
    ];

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function transaction(){
        return $this->belongsTo(PurchasesTransaction::class,'transcode','transcode');
    }

}
