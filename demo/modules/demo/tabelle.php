<?php
include_once '../../config_start.php';
$page->init('demo_tabelle','Tabelle');
include_once ROOT_HDD_CORE.'/core/classes/table.php';

$query_lorumipsum=dbio_SELECT("demo_lorumipsum");
$tabelle=new table($query_lorumipsum);
$tabelle->set_options(true, true, true, 'demo_lorumipsum');
$page->say($tabelle);

$tbl2=array(
	array(
			"id"=>1,
			"Yap"=>html_checkbox(null,true),
			"Zing"=>html_checkbox(),
			"Wiggle"=>html_checkbox(null,true),
			"Flip"=>html_checkbox(),
			"Shnizzle"=>html_checkbox(null,true),
			"Loo"=>html_checkbox(null,true),
			"Meep"=>html_checkbox(),
			"Slap"=>html_checkbox(null,true),
			"Hum"=>html_checkbox(null,true),
			"Flab"=>html_checkbox(),
			"Dobbadingle"=>html_checkbox(null,true),
			"Blap"=>html_checkbox(),
	),
	array(
			"id"=>2,
			"Yap"=>html_checkbox(),
			"Zing"=>html_checkbox(null,true),
			"Wiggle"=>html_checkbox(null,true),
			"Flip"=>html_checkbox(),
			"Shnizzle"=>html_checkbox(null,true),
			"Loo"=>html_checkbox(),
			"Meep"=>html_checkbox(null,true),
			"Slap"=>html_checkbox(),
			"Hum"=>html_checkbox(null,true),
			"Flab"=>html_checkbox(null,true),
			"Dobbadingle"=>html_checkbox(),
			"Blap"=>html_checkbox(null,true),
	),
);
$tabelle2=new table($tbl2,"wide demo_tbl2",false);
$tabelle2->col_highlight=true;
$page->focus="input[type=search]";
$page->say($tabelle2);

$page->send();
exit;//============================================================================================
?>