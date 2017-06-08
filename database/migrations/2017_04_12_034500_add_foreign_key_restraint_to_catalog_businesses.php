<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyRestraintToCatalogBusinesses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('catalog_businesses', function (Blueprint $table) {
			$table->foreign('address_id')->references('id')->on('addresses');
			$table->foreign('phone_number_id')->references('id')->on('phone_numbers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('catalog_businesses', function (Blueprint $table) {
			$table->dropForeign(['address_id']);
			$table->dropForeign(['phone_number_id']);
        });
    }
}
