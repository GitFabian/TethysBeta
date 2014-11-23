<?php
include_once '../../config_start.php';
$page->init('myqueries_index','Index');
include_once ROOT_HDD_CORE.'/core/classes/form.php';
$page->add_library(ROOT_HTTP_CORE."/demo/modules/myqueries/toolbox.js");
/*?*/include_datatables();
include_once ROOT_HDD_CORE.'/core/classes/table.php';
include_once ROOT_HDD_CORE.'/demo/modules/myqueries/index_.php';

if ($r=request_value("response")) $page->message_info($r);
$save=request_command("save");

/*
 * Views: Queries aus Datenbank
 */
if(USER_ADMIN){
	$query=dbio_SELECT("myqueries_queries",(USER_ADMIN?null:""));
}else{
	$query=dbio_SELECT("myqueries_user_query","user=".USER_ID,"q.id,name",array(
		new dbio_leftjoin("query", "myqueries_queries", "q"),
	));
}
$views=array();
foreach ($query as $row) {
	$views[]=new menu_topic2($row["id"], $row["name"]);
}
$view=$page->init_views(null, $views);

if ($view){
	$berechtigung=(USER_ADMIN||dbio_SELECT("myqueries_user_query","user=".USER_ID." AND query=$view"));
}else{
	$berechtigung=false;
}

$page->focus="input[type=text],textarea";

$target="";
if (request_command("view")){
	if($berechtigung){
		$target=get_view($view);
	}else{
		$target="Keine Berechtigung!";
	}
}

$name="";
$desc="";
$query="";
$con="0";
if($view&&$berechtigung){
	$q=dbio_SELECT_SINGLE("myqueries_queries",$view);
	$name=$q['name'];
	$desc=$q['beschreibung'];
	$query=$q['query'];
	$con=$q['connection'];
}

/*
 * Verbindungen
 */
$cons=array();
if(USER_ADMIN){
	$cons["0"]="(Default)";
	$query_cons=dbio_SELECT("myqueries_connections");
}else{
	$query_cons=dbio_SELECT("myqueries_admins","user=".USER_ID,"c.id, name, username, server, dbase",array(
		new dbio_leftjoin("con", "myqueries_connections", "c")
	));
}
foreach ($query_cons as $row) {
	$connection=$row['name'];
	if(!$connection)$connection=$row['username']."@".$row['server']."/".$row['dbase'];
	$id=$row['id'];
	$cons[$id]=$connection;
}

/*
 * Formular
 */

$form=new form(null,null);
$form->tag="div";
$form->class="form";
$form->submit_bool=false;

if($cons){
	$form->add_field($ff=new form_field("connection",null,request_value('connection',$con),'SELECT',null,$cons,"id_con"));
	if(count($cons)==1){
		$ff->outer_class="invisible";
	}
}
if($save){
	$form->add_field(new form_field("name",null,request_value('name',$name),'TEXT',null,null,"id_name"));
}
if($save||$view){
	$form->add_field(new form_field("beschreibung",null,request_value('beschreibung',$desc),'TEXTAREA',null,null,"id_desc"));
}
if($cons){
	$form->add_field(new form_field("query",null,request_value('query',$query),'TEXTAREA',null,null,"id_query"));
}
if($cons){
	$form->buttons.=html_button2("Ansehen","view('".ROOT_HTTP_CORE."','".CFG_EXTENSION."');");
// 	$form->buttons.=html_button2("Exportieren");
}else{
	$form->buttons.=html_a_button("Ansehen","?cmd=view&view=$view");
// 	$form->buttons.=html_a_button("Exportieren","#");
}
if($cons){
	if($save){
		$form->buttons.=html_button2("Speichern","save('".ROOT_HTTP_CORE."','".CFG_EXTENSION."');");
	}else{
		$form->buttons.=html_button2("Speichern","save_create('".ROOT_HTTP_CORE."','".CFG_EXTENSION."');");
	}
	if($view){
// 		$form->buttons.=html_button2("Löschen");
	}
}
if($form->field_groups)#if ($cons||$view)
	$page->say($form->toHTML());

$page->say(html_div($target,"","target"));

$datatable=new datatable("table");
$page->add_inline_script("function datatable_exe(){ ".$datatable->get_execute()." }");

page_send_exit();//===============================================================================
function get_view($id){
	global $page;
	$query=dbio_SELECT_SINGLE("myqueries_queries", $id);
	$command=$query['query'];
	$con=$query['connection'];
	$connection=get_connection($con);
	$data=dbio_query_to_array($command,$connection);
	$data=array_htmlentities_pre($data);
	$table=new table($data);
	$page->focus="input[type=search]";
	$html=$table->toHTML();
	return $html;
}
?>