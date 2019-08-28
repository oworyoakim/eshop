<?php
/**
 * Created by PhpStorm.
 * User: Yoakim
 * Date: 9/17/2018
 * Time: 2:33 PM
 */

namespace App\Repositories\System;

use App\Models\User;
use App\Repositories\GenericRepository;

class UserRepository extends GenericRepository implements IUserRepository
{

    /**
     * BusinessRepository constructor.
     * @param User $model
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }

}