<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class WelcomeController extends Controller
{
    
	/**
	* 	Get article list by most viewed
	* 
	*	@return Response
	*/
	public function index()
	{
		$storage=Redis::Connection();
		//fetch top 3 [0,1,2]
		$popularArticles=$storage->zRevRange('articleViews',0,2);
		
		foreach($popularArticles as $value)
		{
			$id=str_replace('article:','',$value);
			echo "Article #".$id." is popular <br/>";
		}
	}	
}
