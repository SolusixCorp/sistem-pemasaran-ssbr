<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CreateIncomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('incomes')) {
            Schema::create('incomes', function (Blueprint $table) {
                $table->id();
                $table->date('date')->default(Carbon::now()->format('Y-m-d'));
                $table->foreignId('order_id')->constrained('orders');
                $table->float('total')->default(0);
                $table->float('item_expense')->default(0);
                $table->float('income')->default(0);
                $table->float('income_sales')->default(0);
                $table->float('income_purchase')->default(0);
                $table->float('income_expense')->default(0);
                $table->float('income_nominal')->default(0);
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
        Schema::dropIfExists('incomes');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
