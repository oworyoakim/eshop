<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('expenses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('barcode');
            $table->text('comment')->nullable();
            $table->unsignedInteger('expense_type_id');
            $table->unsignedInteger('branch_id')->default(0);
            $table->unsignedInteger('user_id');
            $table->decimal('amount',15,2)->default(0.00);
            $table->enum('status',['pending','completed','canceled'])->default('completed');
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
        Schema::connection('tenant')->dropIfExists('expenses');
    }
}
