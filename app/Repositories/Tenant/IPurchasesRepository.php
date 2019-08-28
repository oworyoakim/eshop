<?php
/**
 * Created by PhpStorm.
 * User: Yoakim
 * Date: 9/17/2018
 * Time: 2:28 PM
 */

namespace App\Repositories\Tenant;


use App\Repositories\IGenericRepository;

interface IPurchasesRepository extends IGenericRepository
{
    public function createPurchaseOrderline(array $data);

    public function getTotalMonthlyPurchases($date);

    public function getBranchTotalMonthlyPurchases($branch_id,$date);

    public function getTotalPurchases($date = null);

    public function getBranchTotalPurchases($branch_id, $date = null);

    public function getTotalReturnedPurchases($date = null);

    public function getBranchTotalReturnedPurchases($branch_id, $date = null);

    public function getAccountsPayable($start_date, $end_date, $branch_id = null);

    public function getProductTotalPurchases($product_id, $start_date, $end_date, $branch_id = null);

    public function getProductTotalVolume($product_id, $start_date, $end_date, $branch_id = null);

    public function getProductTotalReturns($product_id, $start_date, $end_date, $branch_id = null);

    public function getProductTotalDiscount($product_id, $start_date, $end_date, $branch_id = null);

}