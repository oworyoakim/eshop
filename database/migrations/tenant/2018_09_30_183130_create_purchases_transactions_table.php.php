<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchasesTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('purchases_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->date('transact_date');
            $table->unsignedBigInteger('transcode')->unique();
            $table->unsignedInteger('supplier_id')->nullable();
            $table->unsignedInteger('branch_id')->nullable();
            $table->decimal('gross_amount',15,2)->default(0.00);
            $table->decimal('net_amount',15,2)->default(0.00);
            $table->decimal('paid_amount',15,2)->default(0.00);
            $table->decimal('due_amount',15,2)->default(0.00);
            $table->decimal('vat_rate',15,2)->default(0.00);
            $table->decimal('vat_amount',15,2)->default(0.00);
            $table->decimal('discount_rate',15,2)->default(0.00);
            $table->decimal('discount_amount',15,2)->default(0.00);
            $table->enum('status',['pending','received','canceled'])->default('received');
            $table->enum('payment_status',['settled','partial','pending','canceled'])->default('settled');
            $table->string('receipt')->nullable();
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
        Schema::connection('tenant')->dropIfExists('purchases_transactions');
    }
}
