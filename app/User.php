<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Database\Eloquent\Builder;

//GLOBAL SCOPES INJECT
//use Illuminate\Database\Eloquent\Builder;

class User extends Authenticatable
{
    use Notifiable;
	
	//TRAIT FOR SOFT DELETES PRETEND
	//use SoftDeletes;
	//field connected to Carbon libraray for manipulatinfg dates
	//CARBON DATES : protected $dates = ['deleted_at'];
	//NPR.Carbon::now()->diffInYears( $user->deleted_at );
	
	//CHANIGIN DEFUALT TABLE NAME AND PRIMARY KEY
	//protected $primaryKey = 'Contacts_ID';
	//protected $table = 'TblContacts';
	

	/*=======GLOBAL SCOPES-: Observebleas and Events=============*/
	/*protected static function boot()
	{
		parent::boot();

		static::addGlobalScope('age', function (Builder $builder) {
			$builder->where('age', '>', 8);
		});
	}*/
	
	/*====================*/
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];
	
	//disbale mass assignment
	protected $guard = [
        
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
	/************ELOQUENT RELATIONSHIPS *****************************/
	
	//HAS MANY SOMETHING
	function hamsters() {
		return $this->hasMany('App\Hamster');
	}
	
	//MANY TO MANY pivot example WITH ROLE FIELD ADDITIONAL
	public function hamsters()
	{
		return $this->belongsToMany('App\Hamster')->withPivot('role');
	}
	//hamster model insert:
	public function users()
	{
		return $this->belongsToMany('App\User')->withPivot('role');
	}

	
	//UDE I HAMSTER MODEL
	function user() {
		return $this->belongsTo('App\User');
	}
	
	//IN HAMSTER
	
		
	/**************************/
	//FORMATING FIELDS IN DATABASE ACCESSORS -GETTERS 
	function getNameAttribute($value)
	{
		return strtoupper($value);
	}
	
	//WITHOUT FORMATIING ACCESS ACCESSORS -GETTERS without formatiiong
	function getOriginalName($value)
	{
		return $value;
	}
	
	function getIdNameAttribute(){
		return $this->attributes['id'] . ':' . $this->attributes['name'];
	}
	
	//SETTERS -MUTATORS before write back to database
	function setNameAttribute($value){
		return $this->attributes['name'] = strtoupper($value);
	}
	
	
	
}
