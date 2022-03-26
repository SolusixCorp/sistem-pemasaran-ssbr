<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBarangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('barangs')) {
            Schema::create('barangs', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->integer('category_id');
                $table->integer('supplier_id');
                $table->string('merk');
                $table->integer('selling_price');
                $table->integer('buying_price');
                $table->float('discount');
                $table->string('discount_type');
                $table->integer('stock');
                $table->string('status');
                $table->text('barang_photo_path')->nullable();
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
        Schema::dropIfExists('barangs');
    }
}
