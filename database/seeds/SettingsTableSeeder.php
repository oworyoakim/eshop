<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            ['setting_key' => 'company_name', 'setting_value' => 'eShop'],
            ['setting_key' => 'company_address', 'setting_value' => 'https://www.eshop.kim'],
            ['setting_key' => 'company_currency', 'setting_value' => 'UGX'],
            ['setting_key' => 'company_website', 'setting_value' => 'https://www.eshop.kim'],
            ['setting_key' => 'company_country', 'setting_value' => 'UGANDA'],
            ['setting_key' => 'tenants_db_prefix', 'setting_value' => 'eshop'],
            ['setting_key' => 'system_version', 'setting_value' => '1.0'],
            ['setting_key' => 'sms_enabled', 'setting_value' => '1'],
            ['setting_key' => 'active_sms', 'setting_value' => 'clickatell'],
            ['setting_key' => 'portal_address', 'setting_value' => 'P.O BOX 7066, KAMPALA'],
            ['setting_key' => 'company_email', 'setting_value' => 'info@eshop.kim'],
            ['setting_key' => 'company_otheremail', 'setting_value' => 'support@eshop.kim'],
            ['setting_key' => 'currency_symbol', 'setting_value' => 'UGX'],
            ['setting_key' => 'currency_position', 'setting_value' => 'left'],
            ['setting_key' => 'company_logo', 'setting_value' => 'logo.jpg'],
            ['setting_key' => 'twilio_sid', 'setting_value' => ''],
            ['setting_key' => 'twilio_token', 'setting_value' => ''],
            ['setting_key' => 'twilio_phone_number', 'setting_value' => ''],
            ['setting_key' => 'routesms_host', 'setting_value' => ''],
            ['setting_key' => 'routesms_username', 'setting_value' => ''],
            ['setting_key' => 'routesms_password', 'setting_value' => ''],
            ['setting_key' => 'routesms_port', 'setting_value' => ''],
            ['setting_key' => 'sms_sender', 'setting_value' => ''],
            ['setting_key' => 'clickatell_username', 'setting_value' => ''],
            ['setting_key' => 'clickatell_password', 'setting_value' => ''],
            ['setting_key' => 'clickatell_api_id', 'setting_value' => ''],
            ['setting_key' => 'paypal_email', 'setting_value' => 'info@eshop.kim'],
            ['setting_key' => 'currency', 'setting_value' => 'UGX'],
            ['setting_key' => 'password_reset_subject', 'setting_value' => 'Password reset instructions'],
            ['setting_key' => 'password_reset_template', 'setting_value' => 'Password reset instructions'],
            ['setting_key' => 'cron_last_run', 'setting_value' => '2017-10-18 12:01:22'],
            ['setting_key' => 'enable_cron', 'setting_value' => '0'],
            ['setting_key' => 'allow_self_registration', 'setting_value' => '0'],
            ['setting_key' => 'allow_client_login', 'setting_value' => '1'],
            ['setting_key' => 'welcome_note', 'setting_value' => 'Welcome to our company. You can login with your username and password'],
            ['setting_key' => 'allow_client_apply', 'setting_value' => '1'],
            ['setting_key' => 'enable_online_payment', 'setting_value' => '1'],
            ['setting_key' => 'paypal_enabled', 'setting_value' => '1'],
            ['setting_key' => 'paynow_enabled', 'setting_value' => '1']
        ]);
    }

}


