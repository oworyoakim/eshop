<?php
/**
 * Created by PhpStorm.
 * User: Yoakim
 * Date: 9/30/2018
 * Time: 4:25 PM
 */

namespace App\Repositories\Tenant;


use App\Models\Tenant\BusinessSetting as Setting;
use App\Repositories\GenericRepository;

class SettingRepository extends GenericRepository implements ISettingRepository
{
    public function __construct(Setting $model)
    {
        $this->model = $model;
    }

    public function get($key)
    {
        if ($setting = $this->findOneBy([
            'setting_key' => $key
        ])) {
            return $setting->setting_value;
        }
        return null;
    }

    public function set($key, $value)
    {
        if ($setting = $this->findOneBy([
            'setting_key' => $key
        ])) {
            return $this->update($setting->id,[
                'setting_value' => $value
            ]);
        }
        return $this->create([
            'setting_key' => $key,
            'setting_value' => $value
        ]);
    }
}