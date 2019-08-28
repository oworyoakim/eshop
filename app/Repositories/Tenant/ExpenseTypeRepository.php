<?php
/**
 * Created by PhpStorm.
 * User: Yoakim
 * Date: 9/17/2018
 * Time: 4:40 PM
 */

namespace App\Repositories\Tenant;


use App\Models\Tenant\ExpenseType;
use App\Repositories\GenericRepository;

class ExpenseTypeRepository extends GenericRepository implements IExpenseTypeRepository
{
    /**
     * ExpenseTypeRepository constructor.
     * @param ExpenseType $model
     */
    public function __construct(ExpenseType $model)
    {
        $this->model = $model;
    }
}