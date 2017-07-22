<?php

/*
include_once ROOT_HDD_CORE.'/core/toolbox_strings.php';
 */

function tmarkup($string){
	/*
	 * Link der Form "[DESC](http://URL)" (markdown)
	 * =============================================
	 * /  [.*  ]  (http:  /  /.*  )/
	 * /\\[.*\\]\\(http:\\/\\/.*\\)/ Zeichen escapen
	 * 
	 * /\\[.*\\]\\(http:\\/\\/.*\\)/
	 * /\\[.*\\]\\(http:\\/\\/.*\\)/i Case-insensitive
	 * 
	 * /\\[.* \\]\\(http:\\/\\/.* \\)/i
	 * /\\[.+?\\]\\(http:\\/\\/.+?\\)/i Nicht-leer und nicht gierig
	 * 
	 * /\\[.        +?\\]\\(http:\\/\\/.        +?\\)/i
	 * /\\[[^\\]\\n]+?\\]\\(http:\\/\\/[^\\]\\n]+?\\)/i Keine Klammern und keine Zeilenumbrüche
	 * 
	 * /\\[ [^\\]\\n]+? \\]\\( http:\\/\\/[^\\]\\n]+? \\)/i
	 * /\\[([^\\]\\n]+?)\\]\\((http:\\/\\/[^\\]\\n]+?)\\)/i Gruppen
	 */
	$string=preg_replace("/\\[([^\\]\\n]+?)\\]\\((http:\\/\\/[^\\]\\n]+?)\\)/i", "<a href=\"$2\">$1</a>", $string);
	return $string;
}

function string_genitiv_english($name){
	return $name."'s";
}

?>