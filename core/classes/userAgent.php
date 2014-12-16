<?php

/*
include_once ROOT_HDD_CORE.'/core/classes/userAgent.php';
 */

class userAgent{
	
	static function is_firefox($agent=null){
		return userAgent::get_vendor($agent)=="Firefox";
	}

	/**
	 * http://www.useragentstring.com/pages/Firefox/
	 */
	static function get_vendor($agent=null){
		if($agent===null)$agent=$_SERVER['HTTP_USER_AGENT'];
		if (preg_match("/Firefox/", $agent)) return "Firefox";//Mozilla...; rv:...) Gecko...Firefox
		//IE bis 10:
		if (preg_match("/MSIE/", $agent)) return "Internet Explorer";//Mozilla...MSIE
		if (preg_match("/Chrome/", $agent)) return "Chrome";//Mozilla...AppleWebKit...like Gecko...Chrome...Safari
		if (preg_match("/Safari/", $agent)) return "Safari";//Mozilla...AppleWebKit...like Gecko...Safari
		if (preg_match("/Opera/", $agent)) return "Opera";//Opera...Presto... Version/...
		//IE ab 11:
// 		if (preg_match("/; rv:.*?\\) like Gecko/", $agent)) return "Internet Explorer";//Mozilla...; rv:...) like Gecko
		return null;
	}
	
}

?>