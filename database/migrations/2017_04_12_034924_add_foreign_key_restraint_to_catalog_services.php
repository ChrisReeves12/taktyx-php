<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyRestraintToCatalogServices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('catalog_services', function (Blueprint $table) {
			$table->foreign('address_id')->references('id')->on('addresses');
			$table->foreign('catalog_business_id')->references('id')->on('catalog_businesses');
			$table->foreign('phone_number_id')->references('id')->on('phone_numbers');
			$table->foreign('image_id')->references('id')->on('images');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('catalog_services', function (Blueprint $table) {
            $table->dropForeign(['address_id']);
			$table->dropForeign(['catalog_business_id']);
			$table->dropForeign(['phone_number_id']);
			$table->dropForeign(['image_id']);
        });
    }
}
