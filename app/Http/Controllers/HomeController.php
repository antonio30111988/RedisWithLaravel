<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

/*Summary of Tuts on page http://eloquentbyexample.com */
/*
http://codebyjeff.com/blog
ODLIČNI RESURSI SA LARAVEL PRIMJERIMA
*/
class HomeController extends Controller
{
    public callScopeModelFzunctuion()
	{
		$user="hhh";
	}
	
	public function aboutGlobalScope()
	{
		//EXAMPLE GLOBAL SCOPE
		//App\User::withoutGlobalScope('name')->get();  
	}
	
	public function accessorGetName()
	{
		//call moel function getNameAttribute
		User::find(2)->name; 

		//call moel function getIdNameAttribute
		User::find(2)->idName; 
	} 
	
	public function eloquentQueryExamples()
	{
		//DB Query Builder Approach
		//??no timpestamps CREATED_AT, UPDATED_AT .... LIKE ON MODEL ELOQUENT
		DB::table('users')->insert(['name' => 'Old Yeller', 'email' => 'test@test.com']);  // result "true"
	
		//ELOQUENT APPROACH
		$user = new \App\User();  
		$user->name = 'Tets'; 
		$user->email = 'test@test.com';  
		$user->save();    // result "true"
		
		//IMPROVED ELOQUENT
		\App\User::create(['name' => 'Tets Tester', 'email' => 'test@test.com']);
		//--retrieve "Illuminate\Database\Eloquent\MassAssignmentException with message 'name'"
		//SOLUTION:add 4Fillable in model
		
		//USEFUL FUNCTIONS
		//FOR SARCH REALLAY GUT: findOrNew, firstOrNew/
		
		//IMMEDIATELY CREATE AND SAVE firstOrCreate:
		
		/*findOrNew is simply: "Find a record with primary key X. If you can't find one, return a new, empty model instance"
		firstOrNew: "Find the first record that meets this where condition. If you can't find one, return a new, populated model instance"
		firstOrCreate: "Find the first record that meets this where condition. If you can't find one, make a new, populated model instance and also save it to the database"
		*/
		
		//updateOrCreate(SEARCH, CHANGE)
		//\App\Dogs::updateOrCreate(['id' => 1, 'name' => 'Joe'] , ['age' => 15] );
	} 
	
	public function advancedEloquentQueries()
	{
		//get multiple where conditionals
		return \App\Dogs::select('name', 'age')
			->where('name', 'LIKE', '%an%' )
			->Andwhere('age','<', 6)
			->orWhere(function($q){
			//pass $age variable example
			//->orWhere(function($q) use ($age){
				//dd($q);
				$q->where('age','>', 8);
				$q->whereIn('name', ['Jane', 'Jerry']);
			})
			->get();
			
		//must call model for retirieve result
		$dogs = new \App\Dogs;
		dd($dogs);
		$dogs->get();
		$dogs->find();
		$dogs->first();
		$dogs->all();
		
		//moćni eloqurnt query for if else repalcement
		//->when(condition, true callback, <optional false callback> )
		$ageGroup = 'older';
		$dogs = \App\Dogs::select('name', 'age')
			->when($ageGroup == 'older',
				function($q){
					return $q->where('age','>', 8);
				},
				function($q){
					return $q->where('age','<', 8);
				}
			);
		dd($dogs->get());
		
	}
	
	public function jsonExamples()
	{
		//insert
		\App\Cats::create(
			['info' => json_encode(['name' => 'Fluffy', 'long-hair' => true])]
		);
		
		//update
		\App\Cats::where('info->name', 'Furball')->update(['info->name' => 'Firball']);
		
		//update specific record
		\App\Cats::find(2)->update(['info->name' => 'Firball']);
		
		//get json data
		return \App\Cats::where('info->long-hair', true)->get();
	}
	
	
	public function fetchEloquentRaltionships()
	{
		//assign hamster to user with id 1
		$user = \App\User::find(1); 
		$hamster = new \App\Hamster([ 'name' => 'Furry']);
		
		$user->hamsters()->save($hamster); 
		
		//get results 
		//2 NAČINA ISTO VRAČAJU:
		dd(\App\User::find(1)->hamsters()->get());
		dd(\App\User::find(1)->hamsters);
		
		//*************ADVANCE ELOQUENT RELATIONSHIPS**************
		
		//MAMY TO MANY
		/************************/
		// an existing Hamster from last lesson
		$hamster = \App\Hamster::find(1);

		// a simple "attach"
		$user->hamsters()->attach($hamster->id, ['role' => 'owner']);

		// or instead, a complex "sync"
		$user->hamsters()->sync([ $hamster->id => ['role' => 'owner']]);

		// view our User's hamsters just like before
		dd($user->hamsters);
		
		//ZAKLJUČAK: SYNC JE BOLJI JER NEMA DUPLIKATA I PUTEM POLJA VIŠE VRIJEDNOSTI MOGUĆE UNIJETI
		->sync([1,2,3], false); //sync dodaje noev postojeće zadržava, izbjegava dupliakte time
		
		/************************/
	
		//DEDICATED CONNTROLLER AND MODEL
		
		/*Dvojba prilikom arhitekture aplikacije , kako dohvatai mješavinu pivot tablice, kreirati
		novi kontroler ili koristite nbeki od podtojecih dvaju. Najbolje je kreireati odvojen
		entity koji će predstvljazti pivot tablicu i koristiti taj model u poziv u neka od dva kontrolera*/
		
	}
	
	public function relationshipsQueriesManagment()
	{
		//LOG UPITA NA BAZU:
		
		-instalirat Barry hd ekstenziju:
		https://github.com/barryvdh/laravel-ide-helper
		
		//logiranje sql upita
		//dodano u AppSErviceProvider.php klasu
		\DB::listen(function ($event) {
			dump($event->sql);
			dump($event->bindings);
		});
		
		//1.)RELATIONSHIP METODA VS 2.)DYNAMIC PROPERTIES METODA:
		
		//za svaki dopbavljeni user radi novi uput
		//loše perfomase
		$hamsters = \App\Hamster::get();

		
		foreach ($hamsters as $hamster){
			echo $hamster->user()->first()->name;
		}

		//EAGER LOADING-prikuplja sve idove od user tablice i radi jedan upit WHERE IN idovi
		//with(user) -user .ime fucnkije u model koja poezuje model sa modelom Usera
		//********VAŽNO!!! first() .koristi se ode umjesto get() da se dobije single user a ne cijal kolekcija
		$hamsters = \App\Hamster::with('user')->get();

		foreach ($hamsters as $hamster){
			echo $hamster->user()->first()->name;
		}
	}
	
	public function queryingRelationshipChilren()
	{
		/**************************************/

		//primjer TASKA 1:slanje mail svim korisncima koji imaju barem jednu rolu u hamster tablici pivotu
		$owners = \App\User::join('hamsters', 'user_id', '=', 'users.id')->get()->pluck('email');
		
		dd($owners);
		
		//bez duplikata 2načina:
		// 1.)using DISTINCT
		$owners = \App\User::selectRaw('distinct email')->join('hamsters', 'user_id', '=', 'users.id')->get()->pluck('email');

		// 2.)filtering the Collection
		$owners = \App\User::join('hamsters', 'user_id', '=', 'users.id')->get()->pluck('email')->unique();
		
		//EFIKASNO ELOQUENT RJEŠENJE
		$owners = \App\User::has('gerbils')->get()->pluck('email');
		dd($owners); //ISPIS select * from `users` where exists (select * from `hamsters` where `hamsters`.`user_id` = `users`.`id`);
		//SLANJE MAILA
		foreach (\App\User::has('gerbils')->get() as $owner){
			echo $owner->email;
			foreach ($owner->gerbils as $gerbil){
				echo $gerbil->name;
			}
		}
		
		/**************************************/
		//TASK 2: supply a list of all the Users as well as a count of how many gerbils they have registered:
		
		$owners = \App\User::withCount('gerbils')->get();

		foreach ($owners as $owner) {
			echo $owner->name . ': ' .$owner->gerbils_count;
		}
		/**************************************/
		//TASK 3:
		//We're having User bring their gerbils in for shots, but want to break them into groups
//		based on first letter of their name.
//		Give me a list of all the Users with a count of gerbils whose name starts with "F".

		
		$owners = \App\User::withCount(['gerbils' => function($q){
			$q->where('name','LIKE', 'F%');
		}])->get();

		foreach ($owners as $owner) {
			echo $owner->name . ': ' .$owner->gerbils_count;
		}
		/**************************************/
	}
	
	public function collectionsExample()
	{
		//kolekcije:
		What is a Collection? A Collection is a Laravel class that implements the native php ArrayAccess interface to create an ArrayObject.
		
		//mnoge helper metod eza manipulaciju kolkecijama
		//Primjeri seta istih upita
		dd(\App\User::find(1));
		dd(\App\User::whereId(1)->first());
		dd(\App\User::all()->first());
		
		
	}
}
