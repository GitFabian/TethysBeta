<?php
if(!isset($page)){echo"Seite existiert nicht.";exit;}
$page->init('demo_bsp','Datenblätter');
#include_once ROOT_HDD_CORE.'/core/classes/form.php';
#include_chosen();
include_once ROOT_HDD_CORE.'/core/classes/datasheet.php';
include_once ROOT_HDD_CORE.'/core/log.php';

$id=request_value('id');
if (!$id){
	$query=dbio_SELECT("demo_lorumipsum",null,"id",null,null,true,"1");
	if (!$query) page_send_exit("Kein Datensatz vorhanden! ".html_a_button("Neuer Eintrag", ROOT_HTTP_CORE."/core/edit.".CFG_EXTENSION."?id=NEW&db=demo_lorumipsum"));
	$id=$query[0]['id'];
}

$flubtangle=dbio_SELECT_SINGLE("demo_lorumipsum", $id);
$name=$flubtangle['flubtangle'];
if (!$name)$name="Lorum Ipsum #$id";
$page->say(html_header1($name));

if ($flubtangle){
	$page->say(datasheet::from_db("demo", "demo_lorumipsum", $id));
}else{
	$page->say("(Datensatz nicht vorhanden)");
}

/*
 * Set
 */
$page->say(html_header1("Set-Cards"));
$set=new set("");
$set->add_card($card1=new set_card("Karte 1","Dizzle flong ho whack da razz",null));
$card1->add_data(new set_card_data("name", "Name", "Chef"));
$card1->add_data(new set_card_data("hobbies", "Hobbys", "Quibblenip-bloobing, Zingity"));
$card1->buttons[]=html_a_button("Homepage", "http://qnote.de",null,null,true);
$set->add_card($card2=new set_card("Karte 2","Jingle da zang! Zap ho Maggie ingleblang! Yip bam bizzlerizzle bam duh ting, nip ting a loo dang zip.",
		ROOT_HTTP_CORE."/demo/DATA/core_users/person2.png"));
$card2->add_data(new set_card_data("name", "Name", "Lisa"));
$card2->add_data(new set_card_data("hobbies", "Hobby", "Dee flang"));
$card2->buttons[]=html_a_button("Homepage", "http://sourcecode-snippets.de",null,null,true);
$set->add_card(get_user_setcard_CORE());
$page->say($set);


$page->say(logs_for_entity("demo_lorumipsum",$id));

$page->send();
exit;//============================================================================================
?>