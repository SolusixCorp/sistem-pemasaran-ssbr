<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSuppliersColumnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('suppliers', function (Blueprint $table) {
            $table->renameColumn('id', 'supplier_id');
            $table->renameColumn('name', 'supplier_name');
            $table->renameColumn('address', 'supplier_address');
            $table->renameColumn('phone', 'supplier_phone');
            $table->renameColumn('email', 'supplier_email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
