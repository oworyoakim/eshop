<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('stocks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('supplier_id')->nullable();
            $table->timestamp('expiry_date')->nullable();
            $table->decimal('cost_price',15,2)->default(0.00);
            $table->decimal('sell_price',15,2)->default(0.00);
            $table->decimal('discount',5,2)->default(0.00);
            $table->unsignedInteger('quantity')->default(0);
            $table->unsignedInteger('branch_id')->default(0);
            $table->unsignedInteger('user_id')->nullable();
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
