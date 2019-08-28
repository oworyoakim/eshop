<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->truncate();
        $statement = "INSERT INTO `permissions` (`id`, `parent_id`, `name`, `slug`, `description`) VALUES
        (1, 0, 'Products', 'products', 'Access Products Module'),
        (2, 1, 'View products', 'products.view', 'View Products'),
        (3, 1, 'Update products', 'products.update', 'Update Products'),
        (4, 1, 'Delete products', 'products.delete', 'Delete Products'),
        (5, 1, 'Create products', 'products.create', 'Add Products'),
        (6, 0, 'Product Categories', 'products.categories', 'Access Product Categories Module'),
        (7, 6, 'View Product Categories', 'products.categories.view', 'Product Categories'),
        (8, 6, 'Update Product Categories', 'products.categories.update', 'Update Product Categories'),
        (9, 6, 'Delete Product Categories', 'products.categories.delete', 'Delete Product Categories'),
        (10, 6, 'Create Product Categories', 'products.categories.create', 'Add Product Categories'),
        (11, 0, 'Expenses', 'expenses', 'Access Expenses Module'),
        (12, 11, 'View Expenses', 'expenses.view', 'View Expenses'),
        (13, 11, 'Create Expenses', 'expenses.create', 'Create Expenses'),
        (14, 11, 'Update Expenses', 'expenses.update', 'Update Expenses'),
        (15, 11, 'Delete Expenses', 'expenses.delete', 'Delete Expenses'),
        (16, 0, 'Reports', 'reports', 'Access Reports Module'),
        (17, 0, 'Communication', 'communication', 'Access Communication Module'),
        (18, 17, 'Create Communication', 'communication.create', 'Send Emails & SMS'),
        (19, 17, 'Delete Communication', 'communication.delete', 'Delete Communication'),
        (20, 0, 'Users', 'users', 'Access Users Module'),
        (21, 20, 'View Users', 'users.view', 'View Users '),
        (22, 20, 'Create Users', 'users.create', 'Create users'),
        (23, 20, 'Update Users', 'users.update', 'Update Users'),
        (24, 20, 'Delete Users', 'users.delete', 'Delete Users'),
        (25, 20, 'Manage Roles', 'users.roles', 'Manage user roles'),
        (26, 0, 'Settings', 'settings', 'Manage Settings'),
        (27, 0, 'Audit Trail', 'audit_trail', 'Access Audit Trail'),
        (28, 20, 'Manage Permissions', 'users.permissions', 'Manage user permissions');";
        DB::unprepared($statement);
    }

}
