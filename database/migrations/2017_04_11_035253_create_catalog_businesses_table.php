<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatalogBusinessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalog_businesses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('password');
            $table->string('email', 100)->required()->unique()->index();
            $table->string('remember_digest')->nullable();
            $table->string('activation_digest')->nullable();
            $table->string('reset_digest')->nullable();
			$table->dateTime('reset_digest_expire')->nullable();
            $table->integer('phone_number_id')->unsigned()->nullable();
            $table->integer('address_id')->unsigned()->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->text('summary')->nullable();
            $table->boolean('enterprise')->default(false);
            $table->string('status')->default('inactive');
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
        Schema::dropIfExists('catalog_businesses');
    }
}
