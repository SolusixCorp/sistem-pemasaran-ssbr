<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashFlowTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_flow', function (Blueprint $table) {
            $table->id();
            $table->integer('depo_id');
            $table->datetime('input_date');
            $table->enum('cash_type', ['revenue', 'expense']);
            $table->enum('revenue_type_in', ['product_sales', 'petty_cash', 'another_revenue']);
            $table->enum('expense_type', ['expense', 'transfer']);
            $table->text('notes')->nullable();
            $table->float('amount', 12, 2);
            $table->boolean('is_matched');
            $table->text('upload_file')->nullable();
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
        Schema::dropIfExists('cash_flow');
    }
}
