<?php
/**
 * Created by PhpStorm.
 * User: Yoakim
 * Date: 9/17/2018
 * Time: 2:29 PM
 */

namespace App\Repositories\Tenant;

use App\Models\Tenant\Product;
use App\Repositories\GenericRepository;
use Illuminate\Support\Facades\DB;

class ProductRepository extends GenericRepository implements IProductRepository
{
    /**
     * ProductRepository constructor.
     * @param Product $model
     */
    public function __construct(Product $model)
    {
        $this->model = $model;
    }
}