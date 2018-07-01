<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePincodeStateTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('state', function(Blueprint $table) {
            $table->string('name');
            $table->char('code', 2);
            $table->integer('tin')->unique();

            $table->timestamps();
        });

        Schema::create('pincode', function(Blueprint $table) {
            $table->string('city');
            $table->string('district');
            $table->integer('statetin');
            $table->integer('pincode');
            $table->foreign('statetin')->references('tin')->on('state');
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
        Schema::dropIfExists('pincode');
        Schema::dropIfExists('state');
    }
}
