<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->double('cash')->nullable(0);
            $table->boolean('active')->default(1);
            $table->string('street')->nullable();
            $table->string('colony')->nullable();
            $table->string('postalCode')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->integer('createBy')->nullable();
            $table->string('country')->default('México');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shops');
    }
}
