<?php
/**
 * Created by PhpStorm.
 * User: Yoakim
 * Date: 9/17/2018
 * Time: 2:36 PM
 */

namespace App\Repositories\Tenant;

use App\Models\Tenant\Branch;
use App\Repositories\GenericRepository;

class BranchRepository extends GenericRepository implements IBranchRepository
{
    /**
     * BranchRepository constructor.
     * @param Branch $model
     */
    public function __construct(Branch $model)
    {
        $this->model = $model;
    }
}