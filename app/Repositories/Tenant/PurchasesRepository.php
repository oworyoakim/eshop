<?php
/**
 * Created by PhpStorm.
 * User: Yoakim
 * Date: 9/17/2018
 * Time: 2:29 PM
 */

namespace App\Repositories\Tenant;

use App\Models\Tenant\Purchases;
use App\Models\Tenant\PurchasesTransaction;
use App\Repositories\GenericRepository;
use Illuminate\Support\Facades\DB;

class PurchasesRepository extends GenericRepository implements IPurchasesRepository
{
    /**
     * PurchasesRepository constructor.
     * @param PurchasesTransaction $model
     */
    public function __construct(PurchasesTransaction $model)
    {
        $this->model = $model;
    }

    public function getTotalPurchases($date = null)
    {
        if($date){
            $year = intval(date('Y',strtotime($date)));
            $month = intval(date('n',strtotime($date)));
            $day = intval(date('j',strtotime($date)));
            return $this->model->where('status','<>','canceled')
                ->whereYear('transact_date','=',$year)
                ->whereMonth('transact_date','=',$month)
                ->whereDay('transact_date','=',$day)
                ->sum('net_amount');
        }
        return $this->model->where('status','<>','canceled')->sum('net_amount');
    }

    public function getBranchTotalPurchases($branch_id, $date = null)
    {
        if($date){
            $year = intval(date('Y',strtotime($date)));
            $month = intval(date('n',strtotime($date)));
            $day = intval(date('j',strtotime($date)));
            return $this->model->where('branch_id',$branch_id)
                ->where('status','<>','canceled')
                ->whereYear('transact_date','=',$year)
                ->whereMonth('transact_date','=',$month)
                ->whereDay('transact_date','=',$day)
                ->sum('net_amount');
        }

        return $this->model->where('branch_id',$branch_id)
            ->where('status','<>','canceled')
            ->sum('net_amount');
    }

    public function getTotalMonthlyPurchases($date)
    {
        $year = intval(date('Y',strtotime($date)));
        $month = intval(date('n',strtotime($date)));
        return $this->model->where('status','<>','canceled')
            ->whereYear('transact_date','=',$year)
            ->whereMonth('transact_date','=',$month)
            ->sum('net_amount');
    }

    public function getBranchTotalMonthlyPurchases($branch_id, $date)
    {
        $year = intval(date('Y',strtotime($date)));
        $month = intval(date('n',strtotime($date)));
        return $this->model->where('branch_id',$branch_id)
            ->where('status','<>','canceled')
            ->whereYear('transact_date','=',$year)
            ->whereMonth('transact_date','=',$month)
            ->sum('net_amount');
    }

    public function getTotalReturnedPurchases($date = null)
    {
        if($date){
            $year = intval(date('Y',strtotime($date)));
            $month = intval(date('n',strtotime($date)));
            $day = intval(date('j',strtotime($date)));
            return $this->model->where('status','=','canceled')
                ->whereYear('transact_date','=',$year)
                ->whereMonth('transact_date','=',$month)
                ->whereDay('transact_date','=',$day)
                ->sum('net_amount');
        }
        return $this->model->where('status','=','canceled')->sum('net_amount');
    }

    public function getBranchTotalReturnedPurchases($branch_id, $date = null)
    {
        if($date){
            $year = intval(date('Y',strtotime($date)));
            $month = intval(date('n',strtotime($date)));
            $day = intval(date('j',strtotime($date)));
            return $this->model->where('branch_id',$branch_id)
                ->where('status','=','canceled')
                ->whereYear('transact_date','=',$year)
                ->whereMonth('transact_date','=',$month)
                ->whereDay('transact_date','=',$day)
                ->sum('net_amount');
        }

        return $this->model->where('branch_id',$branch_id)
            ->where('status','=','canceled')
            ->sum('net_amount');
    }

    public function createPurchaseOrderline(array $data){
        return Purchases::create($data);
    }

    public function getAccountsPayable($start_date, $end_date, $branch_id = null){
        if($branch_id){
            return $this->model->payables()
                ->where('branch_id',$branch_id)
                ->whereBetween('transact_date',[$start_date,$end_date])
                ->get();
        }
        return $this->model->payables()
            ->whereBetween('transact_date',[$start_date,$end_date])
            ->get();
    }

    public function getProductTotalPurchases($product_id, $start_date, $end_date, $branch_id = null)
    {
        if($branch_id){
            return DB::table('purchases_transactions')
                ->leftJoin('purchases','purchases_transactions.transcode','=','purchases.transcode')
                ->where('purchases.product_id',$product_id)
                ->whereBetween('purchases_transactions.transact_date',[$start_date,$end_date])
                ->where('purchases.status','complete')
                ->where('purchases_transactions.branch_id',$branch_id)
                ->sum('purchases.net_amount');
        }
        return DB::table('purchases_transactions')
            ->leftJoin('purchases','purchases_transactions.transcode','=','purchases.transcode')
            ->where('purchases.product_id',$product_id)
            ->whereBetween('purchases_transactions.transact_date',$start_date,$end_date)
            ->where('purchases.status','complete')
            ->sum('purchases.net_amount');
    }

    public function getProductTotalVolume($product_id, $start_date, $end_date, $branch_id = null)
    {
        if($branch_id){
            return DB::table('purchases_transactions')
                ->leftJoin('purchases','purchases_transactions.transcode','=','purchases.transcode')
                ->where('purchases.product_id',$product_id)
                ->whereBetween('purchases_transactions.transact_date',[$start_date,$end_date])
                ->where('purchases.status','complete')
                ->where('purchases_transactions.branch_id',$branch_id)
                ->sum('purchases.quantity');
        }
        return DB::table('purchases_transactions')
            ->leftJoin('purchases','purchases_transactions.transcode','=','purchases.transcode')
            ->where('purchases.product_id',$product_id)
            ->whereBetween('purchases_transactions.transact_date',$start_date,$end_date)
            ->where('purchases.status','complete')
            ->sum('purchases.	quantity');
    }

    public function getProductTotalReturns($product_id, $start_date, $end_date, $branch_id = null)
    {
        if($branch_id){
            return DB::table('purchases_transactions')
                ->leftJoin('purchases','purchases_transactions.transcode','=','purchases.transcode')
                ->where('purchases.product_id',$product_id)
                ->whereBetween('purchases_transactions.transact_date',[$start_date,$end_date])
                ->where('purchases_transactions.branch_id',$branch_id)
                ->sum('purchases.returns');
        }
        return DB::table('purchases_transactions')
            ->leftJoin('purchases','purchases_transactions.transcode','=','purchases.transcode')
            ->where('purchases.product_id',$product_id)
            ->whereBetween('purchases_transactions.transact_date',$start_date,$end_date)
            ->sum('purchases.returns');
    }

    public function getProductTotalDiscount($product_id, $start_date, $end_date, $branch_id = null)
    {
        if($branch_id){
            return DB::table('purchases_transactions')
                ->leftJoin('purchases','purchases_transactions.transcode','=','purchases.transcode')
                ->where('purchases.product_id',$product_id)
                ->whereBetween('purchases_transactions.transact_date',[$start_date,$end_date])
                ->where('purchases_transactions.branch_id',$branch_id)
                ->where('purchases.status','complete')
                ->sum('purchases.discount_amount');
        }
        return DB::table('purchases_transactions')
            ->leftJoin('purchases','purchases_transactions.transcode','=','purchases.transcode')
            ->where('purchases.product_id',$product_id)
            ->whereBetween('purchases_transactions.transact_date',$start_date,$end_date)
            ->where('purchases.status','complete')
            ->sum('purchases.discount_amount');
    }


}