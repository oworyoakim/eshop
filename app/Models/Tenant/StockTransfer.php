<?php

namespace App\Models\Tenant;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockTransfer extends Model {

    use SoftDeletes;
    protected $table = 'stock_transfers';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'product_id',
        'supplier_id',
        'request_id',
        'cost_price',
        'sell_price',
        'from_branch_id',
        'to_branch_id',
        'quantity',
        'user_id',
    ];

    public function fromBranch() {
        return $this->belongsTo(Branch::class, 'from_branch_id');
    }

    public function toBranch() {
        return $this->belongsTo(Branch::class, 'to_branch_id');
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function transferRequest() {
        return $this->belongsTo(StockRequest::class,'request_id');
    }

    public function user() {
        return $this->belongsTo(Employee::class,'user_id');
    }

}
