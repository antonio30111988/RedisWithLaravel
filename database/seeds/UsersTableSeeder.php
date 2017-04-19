<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::truncate();

		(new Faker\Generator)->seed(123);

		factory(App\User::class, 50)->create();
		
		/*\App\Dog::create(['name' => 'Joe']);
		\App\Dog::create(['name' => 'Jock']);
		\App\Dog::create(['name' => 'Jackie']);
		\App\Dog::create(['name' => 'Jane']);*/
		
		//CREATE Users
		\App\User::create([
			'id' => 1,
			'name' => 'Jeff',
			'email' => 'jeff@codebyjeff.com',
			'password' => \Hash::make('pass123'),
		]);

		\App\User::create([
			'id' => 2,
			'name' => 'Sam',
			'email' => 'sam@codebyjeff.com',
			'password' => \Hash::make('pass123'),
		]);
    }
}
