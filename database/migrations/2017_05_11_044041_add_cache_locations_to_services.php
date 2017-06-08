<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCacheLocationsToServices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('catalog_services', function (Blueprint $table) {
            $table->double('cache_longitude')->nullable();
            $table->double('cache_latitude')->nullable();
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
            $table->dropColumn('cache_longitude');
            $table->dropColumn('cache_latitude');
        });
    }
}
