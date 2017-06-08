<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('catalog_service_employee', function (Blueprint $table) {
			$table->foreign('address_id')->references('id')->on('addresses');
			$table->foreign('image_id')->references('id')->on('images');
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
        Schema::table('catalog_service_employee', function (Blueprint $table) {
			$table->dropForeign(['address_id']);
			$table->dropForeign(['image_id']);
			$table->dropForeign(['phone_number_id']);
        });
    }
}
