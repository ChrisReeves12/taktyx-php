<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
			$table->string('username')->required();
			$table->integer('address_id')->unsigned()->nullable();
			$table->integer('phone_number_id')->unsigned()->nullable();
			$table->integer('image_id')->unsigned();
            $table->string('password');
            $table->string('activation_digest')->nullable();
            $table->string('remember_digest')->nullable();
            $table->string('reset_digest')->nullable();
            $table->dateTime('reset_digest_expire')->nullable();
            $table->string('status')->default('inactive');
            $table->string('email', 100)->unique()->required()->index();
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
        Schema::dropIfExists('customers');
    }
}
