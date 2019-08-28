<?php
/**
 * Created by PhpStorm.
 * User: Yoakim
 * Date: 9/17/2018
 * Time: 4:29 PM
 */

namespace App\Repositories\Tenant;

use App\Models\Tenant\Customer;
use App\Repositories\GenericRepository;


class CustomerRepository extends GenericRepository implements ICustomerRepository
{

    /**
     * CustomerRepository constructor.
     * @param Customer $model
     */
    public function __construct(Customer $model)
    {
        $this->model = $model;
    }
}