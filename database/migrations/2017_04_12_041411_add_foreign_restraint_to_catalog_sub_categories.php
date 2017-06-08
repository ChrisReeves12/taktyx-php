<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignRestraintToCatalogSubCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('catalog_sub_categories', function (Blueprint $table) {
			$table->foreign('catalog_category_id')->references('id')->on('catalog_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('catalog_sub_categories', function (Blueprint $table) {
			$table->dropForeign(['catalog_category_id']);
        });
    }
}
