<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockRequestProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('stock_request_products', function (Blueprint $table) {
            $table->unsignedInteger('request_id');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('quantity');
            $table->boolean('approved')->default(false);
            $table->unsignedInteger('approved_branch_id')->nullable();
            $table->unsignedInteger('approved_quantity')->default(0);
            $table->unsignedInteger('approved_user_id')->nullable();
            $table->primary(['request_id','product_id']);
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('stock_request_products');
    }
}
