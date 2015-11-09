<?php

/*
include_once ROOT_HDD_CORE.'/core/toolbox_dates.php';
 */

function werktage($von,$bis){
	$von_time=strtotime($von)+43200;
	$bis_time=strtotime($bis)+86399;
	$tage=0;
	for ($i = $von_time; $i < $bis_time; $i+=86400) {
		$wochentag=date("w",$i);
		if ($wochentag!=6/*Samstag*/ && $wochentag!=0/*Sonntag*/){
			$jahr=date("Y",$i);
			$feiertage=feiertage($jahr);
			$tag=date("Y-m-d",$i);
			if (!isset($feiertage[$tag])) $tage++;
		}
	}
	return $tage;
}

$feiertage=null;
function feiertage($jahr){
	global $feiertage;
	if (!isset($feiertage[$jahr])){
		$feiertage[$jahr]=calculate_feiertage($jahr);
	}
	return $feiertage[$jahr];
}

function calculate_feiertage($jahr){
	$feiertage=array();
	
	/*
	 * Feste Feiertage
	 */
	#if(setting_get(null, "FT_neujahr"))
		$feiertage["$jahr-01-01"]="Neujahr";
	$feiertage["$jahr-05-01"]="Tag der Arbeit";
	$feiertage["$jahr-10-03"]="Tag der Deutschen Einheit";
	$feiertage["$jahr-12-25"]="Erster Weihnachtstag";
	$feiertage["$jahr-12-26"]="Zweiter Weihnachtstag";
	
	/*
	 * Weitere feste Feiertage
	 */
	$weitere=setting_get(null, "FT_weitere_fest");
	if($weitere){
		$weitere=explode("\n", $weitere);
		foreach ($weitere as $w) {
			$w=trim($w);
			if ($w){
				$w=explode(":", $w);
				if(count($w)>=2){
					$datum=explode(".",$w[0]);
					if(count($datum)>=2){
						$datum=trim($datum[0]).".".trim($datum[1]).".".$jahr;
						$name=trim($w[1]);
						$ymd=format_datum_to_sql($datum);
						$feiertage[$ymd]=$name;
					}
				}
			}
		}
	}
	
	/*
	 * Bewegliche Feiertage
	 */
	$ostern=easter_date($jahr);//Ostersonntag
	//Karfreitag
		$karfreitag=date("Y-m-d",$ostern-(2*86400));
		$feiertage[$karfreitag]="Karfreitag";
	//Ostermontag
		$ostermontag=date("Y-m-d",$ostern+(1*86400));
		$feiertage[$ostermontag]="Ostermontag";
	//Christi Himmelfahrt (DO)
		$himmelfahrt=date("Y-m-d",$ostern+(39*86400));
		$feiertage[$himmelfahrt]="Christi Himmelfahrt";
	//Pfingsten (Pfingstmontag)
		$pfingsten=date("Y-m-d",$ostern+(50*86400));
		$feiertage[$pfingsten]="Pfingsten";
		
	return $feiertage;
}

function sql_zeitraum_matches_jahr($jahr=null,$von='von',$bis='bis'){
	if($jahr===null) $jahr=date("Y");
	return "(($von <= '$jahr-12-31' AND $von >= '$jahr-01-01') OR ($bis <= '$jahr-12-31' AND $bis >= '$jahr-01-01'))";
}

function alter($geburtstag_str,$heute_ts=null){
	if(!$heute_ts)$heute_ts=time();
	$ts_alt=strtotime($geburtstag_str);
	$jahr_delta=date("Y",$heute_ts)-date("Y",$ts_alt);
	$age = (date("md", $ts_alt ) > date("md",$heute_ts) ? ($jahr_delta - 1) :$jahr_delta );
	return $age;
}

function format_date_sql_to_tmj($sql_date){
	$d=substr($sql_date, 8,2)*1;
	$m=substr($sql_date, 5,2)*1;
	$j=substr($sql_date, 0,4)*1;
	return ($d?$d.".":"").($m?$m.".":($d?"?.":"")).($j?:"");
}

?>