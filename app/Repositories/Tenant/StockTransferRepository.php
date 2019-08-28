<?php
/**
 * Created by PhpStorm.
 * User: Yoakim
 * Date: 9/30/2018
 * Time: 4:25 PM
 */

namespace App\Repositories\Tenant;

use App\Models\Tenant\StockRequest;
use App\Models\Tenant\StockTransfer;
use App\Repositories\GenericRepository;

class StockTransferRepository extends GenericRepository implements IStockTransferRepository
{
    public function __construct(StockTransfer $model)
    {
        $this->model = $model;
    }

    public function getTransfers(){
        return $this->model->all();
    }

    public function getBranchTransfers(int $branch_id){
        return $this->model->where('from_branch_id', $branch_id)->orWhere('to_branch_id',$branch_id)->get();
    }

    public function getRequests()
    {
        return StockRequest::all();
    }

    public function getBranchRequests(int $branch_id)
    {
        return StockRequest::where('requesting_branch_id',$branch_id)->orWhere('requested_branch_id',$branch_id)->get();
    }

    public function createRequest(array $data)
    {
        return StockRequest::create($data);
    }

}