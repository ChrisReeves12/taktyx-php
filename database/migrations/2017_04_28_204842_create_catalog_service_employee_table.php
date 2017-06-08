<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatalogServiceEmployeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalog_service_employee', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('catalog_service_id')->unsigned()->index();
            $table->integer('employee_id')->unsigned()->index();
            $table->boolean('admin')->default(false);
			$table->string('status')->default('active');
			$table->integer('address_id')->unsigned()->nullable();
			$table->integer('image_id')->unsigned()->nullable();
			$table->integer('phone_number_id')->unsigned()->nullable();
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
        Schema::dropIfExists('catalog_service_employee');
    }
}
