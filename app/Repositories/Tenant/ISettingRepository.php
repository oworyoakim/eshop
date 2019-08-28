<?php
/**
 * Created by PhpStorm.
 * User: Yoakim
 * Date: 9/30/2018
 * Time: 4:24 PM
 */

namespace App\Repositories\Tenant;


use App\Repositories\IGenericRepository;

interface ISettingRepository extends IGenericRepository
{
    public function get($key);

    public function set($key, $value);
}