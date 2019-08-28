<?php
/**
 * Created by PhpStorm.
 * User: Yoakim
 * Date: 9/17/2018
 * Time: 4:59 PM
 */

namespace App\Repositories\Tenant;


use App\Models\Tenant\Category;
use App\Repositories\GenericRepository;

class CategoryRepository extends GenericRepository implements ICategoryRepository
{
    /**
     * CategoryRepository constructor.
     * @param Category $model
     */
    public function __construct(Category $model)
    {
        $this->model = $model;
    }
}