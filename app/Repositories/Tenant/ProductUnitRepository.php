<?php
/**
 * Created by PhpStorm.
 * User: Yoakim
 * Date: 9/17/2018
 * Time: 2:29 PM
 */

namespace App\Repositories\Tenant;

use App\Models\Tenant\ProductUnit;
use App\Repositories\GenericRepository;

class ProductUnitRepository extends GenericRepository implements IProductUnitRepository
{
    /**
     * ProductUnitRepository constructor.
     * @param ProductUnit $model
     */
    public function __construct(ProductUnit $model)
    {
        $this->model = $model;
    }
}