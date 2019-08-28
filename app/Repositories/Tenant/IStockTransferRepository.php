<?php
/**
 * Created by PhpStorm.
 * User: Yoakim
 * Date: 9/30/2018
 * Time: 4:24 PM
 */

namespace App\Repositories\Tenant;


use App\Repositories\IGenericRepository;

interface IStockTransferRepository extends IGenericRepository
{
    public function getTransfers();

    public function getBranchTransfers(int $branch_id);

    public function getRequests();

    public function getBranchRequests(int $branch_id);

    public function createRequest(array $data);
}