<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('purchases', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('transcode');
            $table->unsignedInteger('product_id');
            $table->decimal('cost_price',15,2)->default(0.00);
            $table->decimal('quantity',10,2)->default(0.00);
            $table->decimal('returns',10,2)->default(0.00);
            $table->decimal('gross_amount',15,2)->default(0.00);
            $table->decimal('net_amount',15,2)->default(0.00);
            $table->decimal('discount_rate',15,2)->default(0.00);
            $table->decimal('discount_amount',15,2)->default(0.00);
            $table->enum('status',['complete','partial','returned'])->default('complete');
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
        Schema::connection('tenant')->dropIfExists('purchases');
    }
}
