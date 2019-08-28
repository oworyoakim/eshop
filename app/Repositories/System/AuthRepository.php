<?php
/**
 * Created by PhpStorm.
 * User: Yoakim
 * Date: 9/17/2018
 * Time: 2:33 PM
 */

namespace App\Repositories\System;

use Sentinel;


class AuthRepository implements IAuthRepository
{
    /**
     * @return bool
     */
    public function hasUser()
    {
        return Sentinel::getUser() ?: false;
    }


}