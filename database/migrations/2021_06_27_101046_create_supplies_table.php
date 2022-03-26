<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


class CreateSuppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('supply')) {
            Schema::create('supply', function (Blueprint $table) {
                $table->id();
                $table->datetime('supply_date')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->string('supplier_id')->constrained("supply")->onDelete('cascade')->onUpdate('cascade');
                $table->foreignId('barang_id')->constrained('barangs');
                $table->integer('qty')->default(0);
                $table->float('total')->default(0);
                $table->string('notes')->default("-");
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
        Schema::dropIfExists('supply');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
