<?php

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */

Route::group(['namespace' => 'Tenant','domain' => '{subdomain}.' . config('app.url_base'),'middleware' => ['tenantConnection']], function () {

    Route::get('', 'HomeController@index');

    Route::get('/test', 'HomeController@test');

    Route::get('/login', 'LoginController@index');

    Route::post('/login', 'LoginController@processLogin');

    Route::get('/logout', 'LoginController@logout');

    Route::post('/forgot-password', 'ResetPasswordController@processForgotPassword');
    Route::get('/reset-password/{email}/{resetCode}', 'ResetPasswordController@resetPassword');
    Route::post('/reset-password/{email}/{resetCode}', 'ResetPasswordController@processResetPassword');

    Route::group(['prefix'=>'account'], function (){
        Route::get('', 'EmployeesController@profile');
        Route::get('profile', 'EmployeesController@profile');
        Route::post('', 'EmployeesController@processProfile');
        Route::post('profile', 'EmployeesController@processProfile');
        Route::post('changePassword', 'EmployeesController@processChangePassword');
    });


    Route::group(['prefix' => 'cashier', 'middleware' => 'cashier'], function () {
        Route::get('', 'CashierController@index');
        Route::get('dashboard', 'CashierController@index');

        Route::group(['prefix' => 'sales'], function () {
            Route::get('', 'SalesController@index');
            Route::get('create', 'SalesController@create');
            Route::post('create', 'SalesController@createProcess');
            Route::get('show/{id}', 'SalesController@show');
            Route::get('print/{transcode}', 'SalesController@print');
            Route::get('update/{id}', 'SalesController@update');
            Route::post('update/{id}', 'SalesController@updateProcess');
            Route::delete('cancel/{id}', 'SalesController@cancel');
        });

        Route::group(['prefix' => 'expenses'], function () {
            Route::get('', 'ExpensesController@index');
            Route::get('create', 'ExpensesController@create');
            Route::post('create', 'ExpensesController@createProcess');
            Route::get('show/{id}', 'ExpensesController@show');
            Route::get('update/{id}', 'ExpensesController@update');
            Route::post('update/{id}', 'ExpensesController@updateProcess');
            Route::delete('cancel/{id}', 'ExpensesController@cancel');
        });
    });


    Route::group(['prefix' => 'manager', 'middleware' => 'manager'], function () {
        Route::get('', 'ManagerController@index');
        Route::get('dashboard', 'ManagerController@index');


        Route::group(['prefix' => 'branches'], function () {
            Route::get('', 'BranchesController@index');
            Route::get('create', 'BranchesController@create');
            Route::post('create', 'BranchesController@createProcess');
            Route::get('show/{id}', 'BranchesController@show');
            Route::get('update/{id}', 'BranchesController@update');
            Route::post('update/{id}', 'BranchesController@updateProcess');
            Route::delete('delete/{id}', 'BranchesController@delete');
        });

        Route::group(['prefix'=>'stocks'], function (){
            Route::get('', 'StocksController@index');
            Route::get('adjust', 'StocksController@adjust');
            Route::post('adjust', 'StocksController@processAdjust');

            Route::group(['prefix'=>'transfer'],function (){
                Route::get('', 'StockTransfersController@index');
                Route::get('requests', 'StockTransfersController@requests');
                Route::post('requests', 'StockTransfersController@processRequest');
                Route::post('approve', 'StockTransfersController@processApprove');
            });
        });


        Route::group(['prefix' => 'expenses'], function () {
            Route::get('', 'ExpensesController@index');
            Route::get('create', 'ExpensesController@create');
            Route::post('create', 'ExpensesController@createProcess');
            Route::get('show/{id}', 'ExpensesController@show');
            Route::get('update/{id}', 'ExpensesController@update');
            Route::post('update/{id}', 'ExpensesController@updateProcess');
            Route::delete('delete/{id}', 'ExpensesController@delete');


            Route::group(['prefix' => 'types'], function () {
                Route::get('', 'ExpenseTypesController@index');
                Route::get('create', 'ExpenseTypesController@create');
                Route::post('create', 'ExpenseTypesController@createProcess');
                Route::get('show/{id}', 'ExpenseTypesController@show');
                Route::get('update/{id}', 'ExpenseTypesController@update');
                Route::post('update/{id}', 'ExpenseTypesController@updateProcess');
                Route::delete('delete/{id}', 'ExpenseTypesController@delete');
            });
        });

        Route::group(['prefix' => 'sales'], function () {
            Route::get('', 'SalesController@index');
            Route::get('create', 'SalesController@create');
            Route::post('create', 'SalesController@createProcess');
            Route::get('show/{transcode}', 'SalesController@show');
            Route::get('print/{transcode}', 'SalesController@print');
            Route::get('pdf/{transcode}', 'SalesController@pdfInvoice');
            Route::get('update/{id}', 'SalesController@update');
            Route::post('update/{id}', 'SalesController@updateProcess');
            Route::delete('delete/{id}', 'SalesController@delete');
        });

        Route::group(['prefix' => 'purchases'], function () {
            Route::get('', 'PurchasesController@index');
            Route::get('create', 'PurchasesController@create');
            Route::post('create', 'PurchasesController@createProcess');
            Route::get('show/{id}', 'PurchasesController@show');
            Route::get('update/{id}', 'PurchasesController@update');
            Route::post('update/{id}', 'PurchasesController@updateProcess');
            Route::delete('delete/{id}', 'PurchasesController@delete');
        });

        Route::group(['prefix' => 'customers'], function () {
            Route::get('', 'CustomersController@index');
            Route::post('', 'CustomersController@index');
            Route::post('create', 'CustomersController@store');
            Route::get('show/{id}', 'CustomersController@show');
            Route::get('update/{id}', 'CustomersController@edit');
            Route::post('update/{id}', 'CustomersController@update');
            Route::delete('delete/{id}', 'CustomersController@destroy');
        });

        Route::group(['prefix' => 'suppliers'], function () {
            Route::get('', 'SuppliersController@index');
            Route::get('create', 'SuppliersController@create');
            Route::post('create', 'SuppliersController@createProcess');
            Route::get('show/{id}', 'SuppliersController@show');
            Route::get('update/{id}', 'SuppliersController@update');
            Route::post('update/{id}', 'SuppliersController@updateProcess');
        });

        Route::group(['prefix' => 'products'], function () {
            Route::get('', 'ProductsController@index');
            Route::get('create', 'ProductsController@create');
            Route::post('create', 'ProductsController@createProcess');
            Route::get('show/{id}', 'ProductsController@show');
            Route::get('update/{id}', 'ProductsController@update');
            Route::post('update/{id}', 'ProductsController@updateProcess');
            Route::delete('delete/{id}', 'ProductsController@delete');


            Route::group(['prefix' => 'categories'], function () {
                Route::get('', 'ProductCategoriesController@index');
                Route::get('create', 'ProductCategoriesController@create');
                Route::post('create', 'ProductCategoriesController@createProcess');
                Route::get('show/{id}', 'ProductCategoriesController@show');
                Route::get('update/{id}', 'ProductCategoriesController@update');
                Route::post('update/{id}', 'ProductCategoriesController@updateProcess');
                Route::delete('delete/{id}', 'ProductCategoriesController@delete');
            });

            Route::group(['prefix' => 'units'], function () {
                Route::get('', 'ProductUnitsController@index');
                Route::get('create', 'ProductUnitsController@create');
                Route::post('create', 'ProductUnitsController@createProcess');
                Route::get('show/{id}', 'ProductUnitsController@show');
                Route::get('update/{id}', 'ProductUnitsController@update');
                Route::post('update/{id}', 'ProductUnitsController@updateProcess');
                Route::delete('delete/{id}', 'ProductUnitsController@delete');
            });
        });


        Route::group(['prefix' => 'employees'], function () {
            Route::get('', 'EmployeesController@index');
            Route::get('create', 'EmployeesController@create');
            Route::post('create', 'EmployeesController@createProcess');
            Route::get('show/{id}', 'EmployeesController@show');
            Route::get('update/{id}', 'EmployeesController@update');
            Route::post('update/{id}', 'EmployeesController@updateProcess');
            Route::delete('delete/{id}', 'EmployeesController@destroy');

            Route::group(['prefix' => 'roles'], function () {
                Route::get('', 'RolesController@index');
                Route::get('create', 'RolesController@create');
                Route::post('create', 'RolesController@createProcess');
                Route::get('update/{id}', 'RolesController@update');
                Route::post('update/{id}', 'RolesController@updateProcess');
                Route::delete('delete/{id}', 'RolesController@destroy');
            });

            Route::group(['prefix' => 'permissions'], function () {
                Route::get('', 'PermissionsController@index');
                Route::get('create', 'PermissionsController@create');
                Route::post('create', 'PermissionsController@createProcess');
                Route::get('update/{id}', 'PermissionsController@update');
                Route::post('update/{id}', 'PermissionsController@updateProcess');
                Route::get('delete/{id}', 'PermissionsController@destroy');
            });
        });

        Route::group(['prefix' => 'reports'], function() {
            Route::get('', 'ReportsController@index');
            Route::post('', 'ReportsController@index');
            Route::get('branches_overall', 'ReportsController@branchesOverall');
            Route::post('branches_overall', 'ReportsController@branchesOverall');
            // Sales Reports
            Route::group(['prefix'=>'sales'],function (){
                Route::get('', 'ReportsController@salesReport');
                Route::post('', 'ReportsController@salesReport');
                Route::get('daily', 'ReportsController@dailySales');
                Route::post('daily', 'ReportsController@dailySales');
                Route::get('monthly', 'ReportsController@monthlySales');
                Route::post('monthly', 'ReportsController@monthlySales');
                Route::get('receivable', 'ReportsController@salesReceivable');
                Route::post('receivable', 'ReportsController@salesReceivable');
            });

            //  Purchases Reports
            Route::group(['prefix'=>'purchases'],function (){
                Route::get('', 'ReportsController@purchasesReport');
                Route::post('', 'ReportsController@purchasesReport');
                Route::get('daily', 'ReportsController@dailyPurchasesReport');
                Route::post('daily', 'ReportsController@dailyPurchasesReport');
                Route::get('accounts_payable', 'ReportsController@accountsPayableReport');
                Route::post('accounts_payable', 'ReportsController@accountsPayableReport');
            });

            Route::get('expenses', 'ReportsController@expensesReport');
            Route::post('expenses', 'ReportsController@expensesReport');
            Route::get('expenses/daily', 'ReportsController@dailyExpensesReport');
            Route::post('expenses/daily', 'ReportsController@dailyExpensesReport');
            Route::get('income', 'ReportsController@profitAndLossReport');
            Route::get('balance_sheet', 'ReportsController@balanceSheetReport');
        });

        Route::group(['prefix' => 'settings'], function () {
            Route::get('', 'BusinessSettingsController@index');
            Route::get('update', 'BusinessSettingsController@updateSystem');
            Route::post('update', 'BusinessSettingsController@updateProcess');
        });
    });
});

Route::group(['namespace' => 'System'], function() {
    Route::get('/', 'HomeController@index');

    Route::get('/login', 'LoginController@index');
    Route::post('/login', 'LoginController@processLogin');

    Route::get('/logout', 'LoginController@logout');

    Route::get('/test', 'HomeController@test');

    Route::post('/forgot-password', 'ResetPasswordController@processForgotPassword');
    Route::get('/reset-password/{email}/{resetCode}', 'ResetPasswordController@resetPassword');
    Route::post('/reset-password/{email}/{resetCode}', 'ResetPasswordController@processResetPassword');

    Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function () {
        Route::get('', 'AdminController@index');
        Route::get('dashboard', 'AdminController@index');

        Route::group(['prefix' => 'businesses'], function () {
            Route::get('', 'BusinessesController@index');
            Route::get('create', 'BusinessesController@create');
            Route::post('create', 'BusinessesController@createProcess');
            Route::get('show/{id}', 'BusinessesController@show');
            Route::get('update/{id}', 'BusinessesController@update');
            Route::post('update/{id}', 'BusinessesController@updateProcess');
            Route::post('activate/{id}', 'BusinessesController@activate');
            Route::post('deactivate/{id}', 'BusinessesController@deactivate');
            Route::delete('delete/{id}', 'BusinessesController@delete');
        });


        Route::group(['prefix' => 'users'], function () {
            Route::get('', 'UsersController@index');
            Route::get('list', 'UsersController@index');
            Route::get('create', 'UsersController@create');
            Route::post('create', 'UsersController@createProcess');
            Route::get('show/{id}', 'UsersController@show');
            Route::get('update/{id}', 'UsersController@update');
            Route::post('update/{id}', 'UsersController@updateProcess');
            Route::delete('delete/{id}', 'UsersController@destroy');

            Route::group(['prefix' => 'roles'], function () {
                Route::get('', 'RolesController@index');
                Route::get('create', 'RolesController@create');
                Route::post('create', 'RolesController@createProcess');
                Route::get('update/{id}', 'RolesController@update');
                Route::post('update/{id}', 'RolesController@updateProcess');
                Route::delete('delete/{id}', 'RolesController@destroy');
            });

            Route::group(['prefix' => 'permissions'], function () {
                Route::get('', 'PermissionsController@index');
                Route::get('create', 'PermissionsController@create');
                Route::post('create', 'PermissionsController@createProcess');
                Route::get('update/{id}', 'PermissionsController@update');
                Route::post('update/{id}', 'PermissionsController@updateProcess');
                Route::get('delete/{id}', 'PermissionsController@destroy');
            });
        });

        Route::group(['prefix' => 'settings'], function () {
            Route::get('', 'SettingsController@index');
            Route::post('update', 'SettingsController@update');
            Route::get('update_system', 'SettingsController@updateSystem');
        });
    });

    Route::group(['prefix' => 'account'], function () {
        Route::get('', 'AdminController@profile');
        Route::get('profile', 'AdminController@profile');
        Route::post('', 'AdminController@processProfile');
        Route::post('profile', 'AdminController@processProfile');
        Route::post('changePassword', 'AdminController@processChangePassword');
    });
});

