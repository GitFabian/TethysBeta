<?php

/*
include_once ROOT_HDD_CORE.'/core/classes/userAgent.php';
 */

class userAgent{
	
	static function is_firefox($agent=null){
		if($agent===null)$agent=$_SERVER['HTTP_USER_AGENT'];
		return preg_match("/Firefox/", $agent);
	}
	
}

?>