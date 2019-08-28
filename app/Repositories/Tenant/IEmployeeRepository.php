<?php
/**
 * Created by PhpStorm.
 * User: Yoakim
 * Date: 9/17/2018
 * Time: 2:35 PM
 */

namespace App\Repositories\Tenant;


use App\Repositories\IGenericRepository;

interface IEmployeeRepository extends IGenericRepository
{
    public function getBranchEmployees(int $branch_id);
}