<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('stock_transfers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('from_branch_id');
            $table->unsignedInteger('to_branch_id');
            $table->unsignedInteger('request_id');
            $table->decimal('cost_price',15,2)->default(0.00);
            $table->decimal('sell_price',15,2)->default(0.00);
            $table->unsignedInteger('quantity')->default(0);
            $table->unsignedInteger('user_id');
            $table->timestamps();
            $table->softDeletes();
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
        Schema::connection('tenant')->dropIfExists('stocks');
    }
}
