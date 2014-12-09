<?php
if(!isset($page)){echo"Seite existiert nicht.";exit;}
$page->init('demo_bsp','Tabellen');
include_once ROOT_HDD_CORE.'/core/classes/table.php';
include_once ROOT_HDD_CORE.'/core/classes/form.php';
include_chosen();
include_once ROOT_HDD_CORE.'/core/alertify.php';

/*
 * Tabelle 1
 */
$page->say(html_header1("Tabelle 1"));
$query_lorumipsum=dbio_SELECT("demo_lorumipsum","");
$data=array();
foreach ($query_lorumipsum as $row) {
	$row['flubtangle']="<pre>".escape_html($row['flubtangle'])."</pre>";
	$row['abracadabra']="<pre>".escape_html($row['abracadabra'])."</pre>";
	$data[$row['id']]=$row;
}
$tabelle=new table($data);
// $tabelle->set_header(array(
// 	"flubtangle"=>"Flubtangle",
// 	"abracadabra"=>"Abracadabra",
// ));
$tabelle->set_options(true, true, true, 'demo_lorumipsum','id',null,and_return("demo","flubtangle"));
array_unshift($tabelle->options, html_a_button("Details",
		ROOT_HTTP_CORE."/demo/modules/demo/flubtangle.".CFG_EXTENSION."?id=[ID:id]",
		"tbl_option tbl_details") );
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
#$page->focus="input[type=search]";
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

/*
 * Tabelle 4
 */
$page->say(html_header1("Tabelle 4"));

$query_users=dbio_SELECT("demo_flubtangle_user",null,"u.id,demo_flubtangle_user.flubtangle,u.vorname,u.nachname",array(
	new dbio_leftjoin("user", "core_users", "u"),
));
$members=array();
foreach ($query_users as $user) {
	$gid=$user['flubtangle'];
	if (!isset($members[$gid]))$members[$gid]=array();
	//$members[$gid][$user['id']]=true;
	//Für den Fall, daß Tabelle Read-Only ist:
	$members[$gid][$user['id']]=$user['vorname']." ".$user['nachname'];
}

$query_users=dbio_SELECT("core_users");
$users=array();
foreach ($query_users as $user) {
	$users[$user['id']]=$user['vorname']." ".$user['nachname'];
}

$page->add_inline_script("function update_members(id,e){
		ids=new Array();
		$(e).find(':selected').each(function(){
			ids.push($(this).val());
		});
		ids=ids.join(',');
		".ajax("update_member&id=\"+id+\"&ids=\"+ids+\"","demo","alertify_ajax_response(response);")."
}");

$data=array();
foreach ($query_lorumipsum as $row) {
	$gid=$row['id'];
	
	if (berechtigung("RIGHT_DEMOMGMT")){
		$selected=(isset($members[$gid])?$members[$gid]:null);
		$m=chosen_select_multi("tmp0", $users, $selected, null, "update_members($gid,this);");
	}else{
		$m=(isset($members[$gid])?implode(", ", $members[$gid]):"-/-");
	}
	
	$data[]=array(
			"id"=>$row['id'],
			"flubtangle"=>$row['flubtangle'],
			"members"=>$m,
	);
}

$table=new table($data,"members_direct",false);
$table->set_header(array(
		"flubtangle"=>"Gruppe",
		"members"=>"Mitglieder",
));
$table->set_options(true, true, true, "demo_lorumipsum");
$page->say($table);


$page->send();
exit;//============================================================================================
?>