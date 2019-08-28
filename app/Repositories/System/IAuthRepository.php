<?php
/**
 * Created by PhpStorm.
 * User: Yoakim
 * Date: 9/17/2018
 * Time: 2:32 PM
 */

namespace App\Repositories\System;


interface IAuthRepository
{
    /**
     * @return bool
     */
    public function hasUser();
}