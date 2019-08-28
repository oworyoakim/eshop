<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusinessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('subdomain')->unique();
            $table->string('db_connection')->default('tenant');
            $table->string('db_host')->default(env('DB_HOST', 'localhost'));
            $table->string('db_port')->default(env('DB_PORT', '3306'));
            $table->string('db_name')->nullable();
            $table->boolean('authorized')->default(false);
            $table->string('address');
            $table->string('phone');
            $table->string('city');
            $table->string('country');
            $table->string('personnel_name');
            $table->string('personnel_address');
            $table->string('personnel_phone');
            $table->string('personnel_email')->nullable();
            $table->string('logo')->nullable();
            $table->string('website')->nullable();
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
        Schema::dropIfExists('businesses');
    }
}
