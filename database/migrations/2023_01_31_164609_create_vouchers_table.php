<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('subject');
            $table->string('laboratory');
            $table->date('return_date');
            $table->string('status');
            $table->timestamps();
        });

        Schema::create('equipment_vouchers', function (Blueprint $table) {
            $table->foreignId('equipment_id')->constrained('equipment');
            $table->foreignId('voucher_id')->constrained('vouchers');
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
        Schema::dropIfExists('vouchers');
        Schema::dropIfExists('equipment_vouchers');
    }
}
