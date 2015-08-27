<?php

/*
include_once ROOT_HDD_CORE.'/core/toolbox_finance.php';
 */

function euro_string_to_cent($string){
	$euro=trim($string);
	$trenner=strlen($euro)-3;
	if($trenner>0){
		//3 Zeichen von Rechts:
		$rechts=substr($euro, $trenner);
		$euro=substr($euro, 0, $trenner);
		//Tausender-Trennzeichen weg:
		$euro=preg_replace("/[\\.\\,]/", "", $euro);
		$euro.=$rechts;
	}
	//DezimalPUNKT:
	$euro=preg_replace("/[\\,]/", ".", $euro);
	$cent=$euro*100;
	return $cent;
}

function euro_string_from_cent($cents){
	$vorzeichen=$cents>=0?"+":"-";
	$euro=abs($cents);
	$trenner=strlen($euro)-2;
	if($trenner<0){
		$euro="0,0".$euro;
	}else if($trenner<1){
		$euro="0,".$euro;
	}else{
		$euro=substr($euro, 0, $trenner).",".substr($euro, $trenner);
	}
	return $vorzeichen.$euro;
}

?>