<?php
/**
 * Created by PhpStorm.
 * User: Yoakim
 * Date: 9/17/2018
 * Time: 4:40 PM
 */

namespace App\Repositories\Tenant;


use App\Repositories\IGenericRepository;

interface IExpenseRepository extends IGenericRepository
{
    public function getTotalMonthlyExpenses($date);

    public function getBranchTotalMonthlyExpenses($branch_id,$date);

    public function getTotalExpenses($date = null);

    public function getBranchTotalExpenses($branch_id, $date = null);

    public function getTotalReturnedExpenses($date = null);

    public function getBranchTotalReturnedExpenses($branch_id, $date = null);
}