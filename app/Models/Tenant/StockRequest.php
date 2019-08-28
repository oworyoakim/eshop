<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockRequest extends Model
{
    use SoftDeletes;
    protected $table = 'stock_requests';
    protected $dates = ['deleted_at','request_date'];

    protected $fillable = [
        'request_code',
        'request_date',
        'requesting_branch_id',
        'user_id',
        'is_global',
        'requested_branch_id',
        'status',
        'notes'
    ];

    public function requestingBranch() {
        return $this->belongsTo(Branch::class, 'requesting_branch_id');
    }

    public function requestedBranch() {
        return $this->belongsTo(Branch::class, 'requested_branch_id');
    }

    public function products() {
        return $this->belongsToMany(Product::class,'stock_request_products','request_id','product_id')->withPivot('quantity','approved','approved_branch_id','approved_quantity','approved_user_id');
    }

    public function user() {
        return $this->belongsTo(Employee::class,'user_id');
    }

    public function getApprovedRequests(){
        $products = [];
        foreach ($this->products as $product){
            if ($product->pivot->approved){
                $products[] = $product;
            }
        }
        return $products;
    }

    public function approve($data = []){
        $products = $this->products()->withPivot('quantity','approved','approved_branch_id','approved_quantity','approved_user_id')->whereIn('product_id',array_keys($data))->get();
        dd($products);

        foreach ($products as $product){

        }

    }

}
