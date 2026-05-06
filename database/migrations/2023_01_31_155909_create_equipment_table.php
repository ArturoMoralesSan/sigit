<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->string('num_serie');
            $table->string('control_tag');
            $table->string('product');
            $table->string('brand');
            $table->string('model');
            $table->string('pu');
            $table->string('status');
            $table->integer('quantity');
            $table->string('image');
            $table->timestamps();
        });

        Schema::create('equipment_user', function (Blueprint $table) {
            $table->foreignId('equipment_id')->constrained('equipment');
            $table->foreignId('user_id')->constrained('users');
            $table->integer('quantity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipment');
        Schema::dropIfExists('equipment_user');
    }
}
