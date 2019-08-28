<?php
namespace App\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TenantDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Seed Permissions
        DB::table('permissions')->truncate();
        $statement = "INSERT INTO `permissions` (`id`, `parent_id`, `name`, `slug`, `description`) VALUES
        (1, 0, 'Products', 'products', 'Manage Products'),
        (2, 1, 'View products', 'products.view', 'View Products'),
        (3, 1, 'Update products', 'products.update', 'Update Products'),
        (4, 1, 'Delete products', 'products.delete', 'Delete Products'),
        (5, 1, 'Create products', 'products.create', 'Add Products'),
        (6, 0, 'Categories', 'products.categories', 'Manage Categories'),
        (7, 6, 'View Categories', 'products.categories.view', 'Product Categories'),
        (8, 6, 'Update Categories', 'products.categories.update', 'Update Product Categories'),
        (9, 6, 'Delete Categories', 'products.categories.delete', 'Delete Product Categories'),
        (10, 6, 'Create Categories', 'products.categories.create', 'Add Product Categories'),
        (11, 0, 'Expenses', 'expenses', 'Manage Expenses'),
        (12, 11, 'View Expenses', 'expenses.view', 'View Expenses'),
        (13, 11, 'Create Expenses', 'expenses.create', 'Create Expenses'),
        (14, 11, 'Update Expenses', 'expenses.update', 'Update Expenses'),
        (15, 11, 'Delete Expenses', 'expenses.delete', 'Delete Expenses'),
        (16, 0, 'Reports', 'reports', 'Access Reports Module'),
        (17, 0, 'Communication', 'communication', 'Manage Communication'),
        (18, 17, 'Create Communication', 'communication.create', 'Send Emails & SMS'),
        (19, 17, 'Delete Communication', 'communication.delete', 'Delete Communication'),
        (20, 0, 'Employees', 'employees', 'Manage Employees'),
        (21, 20, 'View Employees', 'employees.view', 'View Employees '),
        (22, 20, 'Create Employees', 'employees.create', 'Create Employees'),
        (23, 20, 'Update Employees', 'employees.update', 'Update Employees'),
        (24, 20, 'Delete Employees', 'employees.delete', 'Delete Employees'),
        (25, 20, 'Manage Roles', 'employees.roles', 'Manage Employee roles'),
        (26, 20, 'Manage Permissions', 'employees.permissions', 'Manage Employee Permissions'),
        (27, 0, 'Settings', 'settings', 'Manage Settings'),
        (28, 0, 'Audit Trail', 'audit_trail', 'Access Audit Trail'),
        (29, 0, 'Branches', 'branches', 'Manage Branches'),
        (30, 29, 'View Branches', 'branches.view', 'View Branches'),
        (31, 29, 'Create Branches', 'branches.create', 'Create Branches'),
        (32, 29, 'Update Branches', 'branches.update', 'Update Branches'),
        (33, 29, 'Show Branches', 'branches.show', 'Show Branches'),
        (34, 29, 'Delete Branches', 'branches.delete', 'Delete Branches'),
        (35, 1, 'Manage Product Units', 'products.units', 'Manage Product Units'),
        (36, 0, 'Manager Dashboard', 'manager.dashboard', 'View Manager Dashboard'),
        (37, 0, 'Cashier Dashboard', 'cashier.dashboard', 'View Cashier Dashboard'),
        (38, 0, 'Product Units', 'products.units', 'Manage Product Units'),
        (39, 38, 'Create Product Units', 'products.units.create', 'Create Product Units'),
        (40, 38, 'View Product Units', 'products.units.view', 'View Product Units'),
        (41, 38, 'Update Product Units', 'products.units.update', 'Update Product Units'),
        (42, 38, 'Delete Product Units', 'products.units.delete', 'Delete Product Units'),
        (43, 16, 'Overall Reports', 'reports.overall', 'Overall Reports'),
        (44, 16, 'Income Statement', 'reports.income', 'View Income Statement Report'),
        (45, 16, 'Balance Sheet', 'reports.balance_sheet', 'View Balance Sheet Report'),
        (46, 16, 'Sales Report', 'reports.sales', 'View Sales Report'),
        (47, 16, 'Purchases Report', 'reports.purchases', 'View Purchases Report'),
        (48, 16, 'Expenses Report', 'reports.expenses', 'View Expenses Report'),
        (49, 0, 'Sales', 'sales', 'Manage Sales'),
        (50, 49, 'View Sales', 'sales.view', 'View Sales'),
        (51, 49, 'Create Sales', 'sales.create', 'Create Sales'),
        (52, 49, 'Update Sales', 'sales.update', 'Update Sales'),
        (53, 49, 'Show Sales', 'sales.show', 'Show Sales'),
        (54, 49, 'Delete Sales', 'sales.delete', 'Delete Sales'),
        (55, 0, 'Purchases', 'purchases', 'Manage Purchases'),
        (56, 55, 'View Purchases', 'purchases.view', 'View Purchases'),
        (57, 55, 'Create Purchases', 'purchases.create', 'Create Purchases'),
        (58, 55, 'Update Purchases', 'purchases.update', 'Update Purchases'),
        (59, 55, 'Show Purchases', 'purchases.show', 'Show Purchases'),
        (60, 55, 'Delete Purchases', 'purchases.delete', 'Delete Purchases'),
        (61, 0, 'Suppliers', 'suppliers', 'Manage Suppliers'),
        (62, 61, 'View Suppliers', 'suppliers.view', 'View Suppliers'),
        (63, 61, 'Create Suppliers', 'suppliers.create', 'Create Suppliers'),
        (64, 61, 'Update Suppliers', 'suppliers.update', 'Update Suppliers'),
        (65, 61, 'Show Suppliers', 'suppliers.show', 'Show Suppliers'),
        (66, 61, 'Delete Suppliers', 'suppliers.delete', 'Delete Suppliers'),
        (67, 0, 'Customers', 'customers', 'Manage Customers'),
        (68, 67, 'View Customers', 'customers.view', 'View Customers'),
        (69, 67, 'Create Customers', 'customers.create', 'Create Customers'),
        (70, 67, 'Update Customers', 'customers.update', 'Update Customers'),
        (71, 67, 'Show Customers', 'customers.show', 'Show Customers'),
        (72, 67, 'Delete Customers', 'customers.delete', 'Delete Customers'),
        (73, 11, 'Manage Expense Types', 'expenses.types', 'Manage Expense Types');";
        DB::unprepared($statement);

        //Seed Product Units
        DB::table('product_units')->truncate();
        $statement = "INSERT INTO `product_units` (`id`, `title`, `slug`, `description`, `user_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
        (1, 'Kilograms', 'kgs', 'Kilograms (1000 grams)', 1, '2018-09-17 19:51:14', '2018-09-19 09:04:17', NULL),
        (2, 'Pieces', 'pcs', 'Pieces', 1, '2018-09-17 19:52:24', '2018-09-20 20:33:20', NULL),
        (3, 'Packets', 'pkts', 'Packets', 1, '2018-09-17 19:52:51', '2018-09-17 19:52:51', NULL),
        (4, 'Litres', 'ltrs', 'Litres', 1, '2018-09-17 19:55:57', '2018-09-17 19:55:57', NULL),
        (5, 'Bars', 'bars', 'Bars', 1, '2018-09-17 19:56:11', '2018-09-17 19:56:11', NULL),
        (6, 'Crates', 'crt', 'Crates', 1, '2018-09-17 19:56:40', '2018-09-17 19:56:40', NULL),
        (7, 'Bottles', 'btls', 'Bottles', 1, '2018-09-17 19:57:11', '2018-09-17 19:57:11', NULL);";
        DB::unprepared($statement);

        // Seed Roles
        DB::table('roles')->truncate();
        DB::table('roles')->insert([
            [
                'slug' => 'businessmanager',
                'name' => 'Business Manager',
                'permissions' => '{"manager.dashboard":true,"products":true,"products.view":true,"products.update":true,"products.delete":true,"products.create":true,"products.units":true,"products.units.view":true,"products.units.update":true,"products.units.delete":true,"products.units.create":true,"products.categories":true,"products.categories.view":true,"products.categories.update":true,"products.categories.delete":true,"products.categories.create":true,"expenses":true,"expenses.view":true,"expenses.create":true,"expenses.update":true,"expenses.delete":true,"expenses.show":true,"expenses.types":true,"reports":true,"reports.overall": true,"reports.sales": true,"reports.purchases": true,"reports.expenses": true,"reports.balance_sheet": true,"reports.income": true,"settings":true,"communication":true,"communication.create":true,"communication.delete":true,"users":true,"users.view":true,"users.create":true,"manager.dashboard":true,"employees":true,"employees.view":true,"employees.create":true,"employees.update":true,"employees.show":true,"employees.delete":true,"employees.roles":true,"employees.permissions":true,"sales":true,"sales.view":true,"sales.create":true,"sales.update":true,"sales.show":true,"sales.delete":true,"purchases":true,"purchases.view":true,"purchases.create":true,"purchases.update":true,"purchases.show":true,"purchases.delete":true,"suppliers":true,"suppliers.view":true,"suppliers.create":true,"suppliers.update":true,"suppliers.show":true,"suppliers.delete":true,"customers":true,"customers.view":true,"customers.create":true,"customers.update":true,"customers.show":true,"customers.delete":true,"branches":true,"branches.view":true,"branches.create":true,"branches.update":true,"branches.delete":true,"stocks":true,"stocks.transfer":true,"stocks.adjust":true}'
            ],
            [
                'slug' => 'branchmanager',
                'name' => 'Branch Manager',
                'permissions' => '{"manager.dashboard":true,"products.categories":true,"products.categories.view":true,"products.categories.update":true,"products.categories.delete":true,"products.categories.create":true,"products":true,"products.view":true,"products.update":true,"products.delete":true,"products.create":true,"expenses.types":true,"expenses.types.view":true,"expenses.types.create":true,"expenses.types.update":true,"expenses.types.delete":true,"expenses":true,"expenses.view":true,"expenses.create":true,"expenses.update":true,"expenses.delete":true,"reports":true,"communication":true,"communication.create":true,"users":true,"users.view":true,"audit_trail":true,"sales":true,"sales.view":true,"sales.create":true,"sales.update":true,"sales.show":true,"sales.delete":true,"purchases":true,"purchases.view":true,"purchases.create":true,"purchases.update":true,"purchases.show":true,"purchases.delete":true}'
            ],
            [
                'slug' => 'cashier',
                'name' => 'Cashier',
                'permissions' => '{"cashier.dashboard":true}'
            ],
        ]);

        // Seed Settings
        DB::table('settings')->truncate();
        $statement = "INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES
        ('company_name', 'eShop'),
        ('company_address', ''),
        ('company_currency', 'UGX'),
        ('company_website', ''),
        ('company_country', 'UGANDA'),
        ('system_version', '1.0'),
        ('portal_address', ''),
        ('company_email', ''),
        ('currency_symbol', 'UGX'),
        ('currency_position', 'left'),
        ('company_logo', 'logo.png'),
        ('favicon', ''),
        ('currency', 'UGX'),
        ('enable_vat',0),
        ('vat',18),
        ('enable_discount',0),
        ('discount',0),
        ('enable_global_margin',1),
        ('profit_margin',15),
        ('password_reset_subject', 'Password reset instructions'),
        ('password_reset_template', 'Password reset instructions'),
        ('welcome_note', 'Welcome to {companyName}. You can login with your email and password'),
        ('company_otheremail', '');";
        DB::unprepared($statement);

        // Seed default Branch
        DB::table('branches')->truncate();
        DB::table('branches')->insert([
            'name'=>'Main',
            'created_at'=> date('Y-m-d H:i:s'),
            'updated_at'=> date('Y-m-d H:i:s')
        ]);

        // Seed default Customer
        DB::table('customers')->truncate();
        DB::table('customers')->insert([
            'name'=>'Walk-In Customer',
            'phone' => '+2567xxxxxxxx',
            'user_id' => 1,
            'created_at'=> date('Y-m-d H:i:s'),
            'updated_at'=> date('Y-m-d H:i:s')
        ]);

    }
}
