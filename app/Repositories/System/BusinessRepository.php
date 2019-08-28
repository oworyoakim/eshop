<?php
/**
 * Created by PhpStorm.
 * User: Yoakim
 * Date: 9/17/2018
 * Time: 2:33 PM
 */

namespace App\Repositories\System;


use App\Models\Business;
use App\Repositories\GenericRepository;

class BusinessRepository extends GenericRepository implements IBusinessRepository
{

    /**
     * BusinessRepository constructor.
     * @param Business $model
     */
    public function __construct(Business $model)
    {
        $this->model = $model;
    }

}