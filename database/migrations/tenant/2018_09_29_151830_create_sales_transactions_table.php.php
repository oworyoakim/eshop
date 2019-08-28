<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('sales_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->date('transact_date');
            $table->unsignedBigInteger('transcode')->unique();
            $table->unsignedInteger('customer_id');
            $table->unsignedInteger('branch_id');
            $table->decimal('gross_amount',15,2)->default(0.00);
            $table->decimal('net_amount',15,2)->default(0.00);
            $table->decimal('vat_rate',15,2)->default(0.00);
            $table->decimal('vat_amount',15,2)->default(0.00);
            $table->decimal('discount_rate',15,2)->default(0.00);
            $table->decimal('discount_amount',15,2)->default(0.00);
            $table->decimal('paid_amount',15,2)->default(0.00);
            $table->decimal('due_amount',15,2)->default(0.00);
            $table->date('due_date')->nullable();
            $table->enum('status',['pending','partially_returned','fully_returned','completed','canceled'])->default('completed');
            $table->enum('payment_status',['settled','partial','pending','canceled'])->default('settled');
            $table->string('receipt')->nullable();
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
        Schema::connection('tenant')->dropIfExists('sales_transactions');
    }
}
