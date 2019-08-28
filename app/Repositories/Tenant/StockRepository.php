<?php
/**
 * Created by PhpStorm.
 * User: Yoakim
 * Date: 9/30/2018
 * Time: 4:25 PM
 */

namespace App\Repositories\Tenant;


use App\Models\Tenant\Stock;
use App\Repositories\GenericRepository;

class StockRepository extends GenericRepository implements IStockRepository
{
    public function __construct(Stock $model)
    {
        $this->model = $model;
    }
}