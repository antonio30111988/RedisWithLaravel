<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

/**
* @decription Main App Contrller , Predis interaction cachiong results
* @author Antono Lolić
* @license Antonio2017
*/
class BlogController extends Controller
{
	/*
	*	Starting route	
	*
	* 	@param id 
	* 	@return Response
	*/
	public function showArticle($id)
	{
		$this->id=$id;
		$storage=Redis::Connection();
		
		if($storage->zScore('articleViews','article:'.$this->id))
		{
			//pipileine-coomannd for sending multiple commands to Redis cache system,
			$storage->pipeline(function($pipe){
				$pipe->zIncrBy('articleViews',1,'article:'.$this->id);
				$pipe->incr('article:'.$this->id.':views'); 
			});
			//increment views of article to display to 3 most viewed articles
			//$storage->zIncrBy('articleViews',1,'article:'.$id); 
		}else
		{
			//increment views by each refresh
			$views=$storage->incr('article:'.$this->id.':views'); 
		
			//increment views variable already exist of article to display to 3 most viewed articles
			$storage->zIncrBy('articleViews',$views,'article:'.$this->id); 
		}
		//get views
		$views=$storage->get('article:'.$this->id.':views'); 

		return "This is an article with id:".$this->id." it has ".$views." views.";
	}
}
