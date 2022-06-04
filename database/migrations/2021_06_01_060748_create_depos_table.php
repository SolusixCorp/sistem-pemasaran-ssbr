<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('depos')) {
            Schema::create('depos', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id');
                $table->enum('type', ['principle', 'freelance']);
                $table->string('city');
                $table->string('address');
                $table->string('phone');
                $table->float('ar_balance');
                $table->string('email')->nullable();    
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('depos');
    }
}
