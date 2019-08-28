<?php
/**
 * Created by PhpStorm.
 * User: Yoakim
 * Date: 9/17/2018
 * Time: 4:51 PM
 */

namespace App\Repositories\Tenant;


use App\Models\Tenant\Supplier;
use App\Repositories\GenericRepository;

class SupplierRepository extends GenericRepository implements ISupplierRepository
{
    /**
     * SupplierRepository constructor.
     * @param Supplier $model
     */
    public function __construct(Supplier $model)
    {
        $this->model = $model;
    }
}