<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockFlowTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_flow', function (Blueprint $table) {
            $table->id();
            $table->integer('depo_id');
            $table->integer('product_id');
            $table->integer('stock');
            $table->datetime('input_date');
            $table->enum('stock_type', ['in', 'out']);
            $table->enum('stockin_category', ['dropping', 'return']);
            $table->enum('stockout_category', ['sales', 'return']);
            $table->integer('qty');
            $table->enum('price_type', ['depo_price', 'consument', 'retail', 'sws', 'ws']);
            $table->float('price');
            $table->integer('remaining_stock');
            $table->boolean('is_delivered');
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
        Schema::dropIfExists('stock_flow');
    }
}
