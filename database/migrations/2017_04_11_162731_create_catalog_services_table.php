<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatalogServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalog_services', function (Blueprint $table) {
            $table->increments('id');
			$table->string('name');
            $table->integer('address_id')->unsigned();
            $table->integer('catalog_business_id')->unsigned()->index();
			$table->integer('phone_number_id')->unsigned()->nullable();
			$table->integer('catalog_sub_category_id')->unsigned()->index();
			$table->integer('image_id')->unsigned();
            $table->string('email')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->text('summary')->nullable();
			$table->text('keywords')->nullable();
			$table->boolean('takt_enabled')->default(true);
            $table->string('status')->default('closed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('catalog_services');
    }
}
