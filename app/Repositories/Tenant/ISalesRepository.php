<?php
/**
 * Created by PhpStorm.
 * User: Yoakim
 * Date: 9/17/2018
 * Time: 2:28 PM
 */

namespace App\Repositories\Tenant;


use App\Models\Tenant\Branch;
use App\Repositories\IGenericRepository;

interface ISalesRepository extends IGenericRepository
{
    public function createSalesOrderline(array $data);

    public function getTopSellingProductsByQuantity();

    public function getBranchTopSellingProductsByQuantity($branch_id);

    public function getTopSellingProductsByPrice();

    public function getBranchTopSellingProductsByPrice($branch_id);

    public function getCurrentMonthTopSellingProducts();

    public function getBranchCurrentMonthTopSellingProducts($branch_id);

    public function getDaysTotalSalesByPrice($date = null);

    public function getBranchDaysTotalSalesByPrice($branch_id, $date = null);

    public function getDaysTotalSalesByQuantity($date = null);

    public function getBranchDaysTotalSalesByQuantity($branch_id, $date = null);

    public function getLatestSales($branch_id = null);

    public function getDaysReturnedSales($date = null);

    public function getBranchDaysReturnedSales($branch_id,$date = null);

    public function getTotalMonthlySales($date);

    public function getBranchTotalMonthlySales($branch_id,$date);

    public function getProductTotalRevenues($product_id,$start_date,$end_date,$branch_id = null);

    public function getProductTotalDiscount($product_id, $start_date, $end_date, $branch_id = null);

    public function getProductTotalReturns($product_id, $start_date, $end_date, $branch_id = null);

    public function getProductTotalVolume($product_id, $start_date, $end_date, $branch_id = null);

    public function getAccountsReceivables($start_date, $end_date, $branch_id = null);

    public function getSalesSummary(string $start_date, string $end_date, int $branch_id = null);

    public function getBranchDailySalesValues(Branch $branch, string $start_date, string $end_date);

    public function getBranchMonthlySalesValues(Branch $branch, string $start_date, string $end_date);

}