<?php
include_once '../../config_start.php';
$page->init('demo_tabelle','Tabelle');
include_once ROOT_HDD_CORE.'/core/classes/table.php';
include_once ROOT_HDD_CORE.'/core/classes/form.php';

/*
 * Tabelle 1
 */
$page->say(html_header1("Tabelle 1"));
$query_lorumipsum=dbio_SELECT("demo_lorumipsum");
$tabelle=new table($query_lorumipsum);
$tabelle->set_options(true, true, true, 'demo_lorumipsum');
$page->say($tabelle);

/*
 * Tabelle 2
 */
$page->say(html_header1("Tabelle 2"));
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

/*
 * Tabelle 3
 */
$page->say(html_header1("Tabelle 3"));

$query_users=dbio_SELECT("demo_flubtangle_user",null,"demo_flubtangle_user.id,demo_flubtangle_user.flubtangle,u.vorname,u.nachname",array(
	new dbio_leftjoin("user", "core_users", "u"),
));
$members=array();
foreach ($query_users as $user) {
	$gid=$user['flubtangle'];
	if (!isset($members[$gid]))$members[$gid]=array();
	$members[$gid][]=$user['vorname']." ".$user['nachname'];
}

$query_lorumipsum=dbio_SELECT("demo_lorumipsum");
$data=array();
foreach ($query_lorumipsum as $row) {
	$gid=$row['id'];
	$data[]=array(
		"id"=>$gid,
		"Gruppe"=>$row['flubtangle'],
		"Mitglieder"=>(isset($members[$gid])?implode(", ", $members[$gid]):"-/-"),
	);
}

$table=new table($data);
$table->set_options(true, true, true, "demo_lorumipsum");
$page->say($table);

$page->send();
exit;//============================================================================================
?>