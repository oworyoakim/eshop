<?php

namespace App\Providers;

use App\Repositories\System\AuthRepository;
use App\Repositories\System\BusinessRepository;
use App\Repositories\System\IAuthRepository;
use App\Repositories\System\IBusinessRepository;
use App\Repositories\System\IUserRepository;
use App\Repositories\System\UserRepository;
use App\Repositories\Tenant\BranchRepository;
use App\Repositories\Tenant\CategoryRepository;
use App\Repositories\Tenant\CustomerRepository;
use App\Repositories\Tenant\EmployeeRepository;
use App\Repositories\Tenant\ExpenseRepository;
use App\Repositories\Tenant\ExpenseTypeRepository;
use App\Repositories\Tenant\IBranchRepository;
use App\Repositories\Tenant\ICategoryRepository;
use App\Repositories\Tenant\ICustomerRepository;
use App\Repositories\Tenant\IEmployeeRepository;
use App\Repositories\Tenant\IExpenseRepository;
use App\Repositories\Tenant\IExpenseTypeRepository;
use App\Repositories\Tenant\IProductRepository;
use App\Repositories\Tenant\IProductUnitRepository;
use App\Repositories\Tenant\IPurchasesRepository;
use App\Repositories\Tenant\ISalesRepository;
use App\Repositories\Tenant\ISettingRepository;
use App\Repositories\Tenant\IStockRepository;
use App\Repositories\Tenant\IStockTransferRepository;
use App\Repositories\Tenant\ISupplierRepository;
use App\Repositories\Tenant\ProductRepository;
use App\Repositories\Tenant\ProductUnitRepository;
use App\Repositories\Tenant\PurchasesRepository;
use App\Repositories\Tenant\SalesRepository;
use App\Repositories\Tenant\SettingRepository;
use App\Repositories\Tenant\StockRepository;
use App\Repositories\Tenant\StockTransferRepository;
use App\Repositories\Tenant\SupplierRepository;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(IAuthRepository::class, AuthRepository::class);
        $this->app->singleton(IBusinessRepository::class,BusinessRepository::class);
        $this->app->singleton(IBranchRepository::class,BranchRepository::class);
        $this->app->singleton(IEmployeeRepository::class,EmployeeRepository::class);
        $this->app->singleton(IProductRepository::class,ProductRepository::class);
        $this->app->singleton(IProductUnitRepository::class,ProductUnitRepository::class);
        $this->app->singleton(ICategoryRepository::class,CategoryRepository::class);
        $this->app->singleton(ICustomerRepository::class,CustomerRepository::class);
        $this->app->singleton(IExpenseTypeRepository::class, ExpenseTypeRepository::class);
        $this->app->singleton(IExpenseRepository::class, ExpenseRepository::class);
        $this->app->singleton(ISupplierRepository::class, SupplierRepository::class);
        $this->app->singleton(ISalesRepository::class,SalesRepository::class);
        $this->app->singleton(IPurchasesRepository::class,PurchasesRepository::class);
        $this->app->singleton(IUserRepository::class,UserRepository::class);
        $this->app->singleton(IStockRepository::class,StockRepository::class);
        $this->app->singleton(IStockTransferRepository::class,StockTransferRepository::class);
        $this->app->singleton(ISettingRepository::class,SettingRepository::class);

    }
}
