<?php

/*
include_once ROOT_HDD_CORE.'/core/rss.php';
 */

/**
 * 
 * http://php.net/manual/de/simplexml.examples-basic.php
 */
function rss_read_url($url,$entry="entry"){
	$content=file_get_contents($url);
	$xml=new SimpleXMLElement($content);
	$entries=$xml->$entry;
	return $entries;
}

?>