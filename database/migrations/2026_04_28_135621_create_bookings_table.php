<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->unsignedBigInteger('area_id');

            $table->text('subject')->nullable();
            $table->string('name');

            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');

            $table->string('status')->default('Pendiente');
            $table->string('color')->default('#F59E0B');

            $table->timestamps();

            $table->foreign('area_id')
                  ->references('id')
                  ->on('areas')
                  ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
}
