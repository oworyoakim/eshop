<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('stock_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('request_code')->unique();
            $table->date('request_date');
            $table->unsignedInteger('requesting_branch_id');
            $table->unsignedInteger('user_id');
            $table->boolean('is_global')->default(true);
            $table->unsignedInteger('requested_branch_id')->nullable();
            $table->enum('status',['pending','partial','approved','canceled'])->default('pending');
            $table->text('notes')->nullable();
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
        Schema::connection('tenant')->dropIfExists('stock_requests');
    }
}
