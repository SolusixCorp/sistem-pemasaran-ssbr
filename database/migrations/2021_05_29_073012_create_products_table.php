<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->integer('category_id');
                $table->string('name');
                $table->text('description')->nullable();
                $table->text('image')->nullable();
                $table->integer('stock');
                $table->float('consument_price');
                $table->float('retail_price');
                $table->float('sub_whole_price');
                $table->float('wholesales_price');
                $table->string('status');
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
        Schema::dropIfExists('products');
    }
}
