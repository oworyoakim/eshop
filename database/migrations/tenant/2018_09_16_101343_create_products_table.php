<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('barcode')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('avatar')->nullable();
            $table->decimal('margin')->default(0.00);
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('unit_id');
            $table->unsignedInteger('branch_id')->default(0);
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
        Schema::connection('tenant')->dropIfExists('products');
    }
}
