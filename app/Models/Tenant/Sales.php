<?php

namespace App\Models\Tenant;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sales extends Model
{
    use SoftDeletes;

    protected $table = 'sales';

    protected $fillable = [
        'transcode',
        'product_id',
        'sell_price',
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
        return $this->belongsTo(SalesTransaction::class,'transcode','transcode');
    }

    public function returnsInwardsAmount(){
        $amount = $this->returns * $this->sell_price;
        $discount = round($amount * $this->discount_rate / 100,0);
        $vat = round($amount * $this->transaction->vat_rate / 100,0);
        return $amount + $vat - $discount;
    }

    public function returnedTaxAmount(){
        $amount = $this->returns * $this->sell_price;
        //$discount = round($amount * $this->discount_rate / 100,0);
        $vat = round($amount * $this->transaction->vat_rate / 100,0);
        return $vat;
    }
}
