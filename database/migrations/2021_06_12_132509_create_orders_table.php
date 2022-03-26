<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->foreignId('kasir_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
                $table->datetime('order_date')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->foreignId('customer_id')->constrained("customer")->onDelete('cascade')->onUpdate('cascade');
                $table->float('total')->default(0);
                $table->float('total_with_discount')->default(0);
                $table->float('discount_percentage')->default(0);
                $table->float('discount_rp')->default(0);
                $table->string('discount_notes')->default("-");
                $table->string('discount_type')->default("rp");
                $table->float('bayar')->default(0);
                $table->float('kembalian')->default(0);
                $table->string('notes')->default("");
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
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::dropIfExists('orders');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
