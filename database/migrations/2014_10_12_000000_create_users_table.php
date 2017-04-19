<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
			
			//json field
			//$table->json('info');
			
            $table->string('password');
            $table->rememberToken();
			
			//add soft delete functionality, field for active or not active set is placed in table
			//FIELD deleted_at CREATED
			//$table->softDeletes();
			
            $table->timestamps();
			
			//MANY TO MANY RELATIONSHIP PIVOT TABLE
			Schema::create('hamster_user', function (Blueprint $table) {
				$table->unsignedInteger('hamster_id');
				$table->unsignedInteger('user_id');
				$table->string('role');
				$table->timestamps();
			});
        });
    }
	
	

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
