<?php

namespace App\Models\Tenant;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stock extends Model {

    use SoftDeletes;
    protected $table = 'stocks';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'product_id',
        'branch_id',
        'supplier_id',
        'user_id',
        'expiry_date',
        'cost_price',
        'sell_price',
        'quantity',
    ];

    public function branch() {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function supplier() {
        return $this->belongsTo(Supplier::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

}
