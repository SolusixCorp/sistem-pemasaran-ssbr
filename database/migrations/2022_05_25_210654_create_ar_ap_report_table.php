<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArApReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ar_ap_report', function (Blueprint $table) {
            $table->id();
            $table->integer('depo_id');
            $table->datetime('payment_date');
            $table->enum('payment_type', ['transfer', 'cash', 'return']);
            $table->text('payment_desc');
            $table->text('payment_file_upload');
            $table->float('amount');
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
        Schema::dropIfExists('ar_ap_report');
    }
}
