<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->increments('id');
			$table->string('email', 100)->unique()->required()->index();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('password');
            $table->string('remember_digest')->nullable();
            $table->string('reset_digest')->nullable();
            $table->dateTime('reset_digest_expire')->nullable();
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
        Schema::dropIfExists('employees');
    }
}
