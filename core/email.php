<?php

/*
include_once ROOT_HDD_CORE.'/core/email.php';
 */

define('BLAT_EXE',ROOT_HDD_CORE.'/core/email/blat.exe');

function email_create_schedule_send($to,$title,$body,$sender=null){
	global $page;
	if (!$to){
		include_once ROOT_HDD_CORE.'/core/alertify.php';
		$page->onload_JS.=alertify_error("Fehler beim eMail-Versand:<br>Keine eMail-Adresse angegeben!");
		return false;
	}
	dbio_INSERT("core_mails", array(
		"erstellt"=>time(),
		"an"=>$to,
		"message"=>$body,
		"sent"=>null,
		"subject"=>$title,
		"attachment"=>null,
		"replyto"=>$sender,
	));
	$page->onload_JS.=ajax_to_alertify("sendmail&id=".mysql_insert_id(),null,true);
	return true;
}

function email_send($id){
	if(!file_exists(BLAT_EXE)){if(USER_ADMIN)echo"!BLAT nicht gefunden!";return;}
	$server=setting_get(null, 'MAIL_SERVER');
	if(!$server){if(USER_ADMIN)echo"!Mails nicht konfiguriert!";return;}
	
	$msg=dbio_SELECT_SINGLE("core_mails", $id);
	
	$message_body=$msg['message'];
	$attachment=$msg['attachment'];
	$bcc=setting_get(null, 'MAIL_BCC');
	$sender=$msg['replyto'];
	
	$commandline='"'.BLAT_EXE.'"';
	
	$history_dir=ROOT_HDD_CORE.'/core/email/history/msg'.$id;
	mkdir($history_dir);
	
	file_put_contents($history_dir.'/msg.txt', $message_body);
	$commandline.=' "'.$history_dir.'/msg.txt"';
	
	$commandline.=' -to "'.blat_escape($msg['an']).'"';
	$commandline.=' -subject "'.blat_escape($msg['subject']).'"';
	$commandline.=' -server '.$server;
	$commandline.=' -f "'.blat_escape(setting_get(null, 'MAIL_FROM')).'"';
	$commandline.=' -u '.setting_get(null, 'MAIL_USER');
	$commandline.=' -pw "'.blat_escape(setting_get(null, 'MAIL_PASS')).'"';
	$commandline.=' -html';
	$commandline.=' -noh2';
	if ($attachment){
		$commandline.=' -attach "'.preg_replace("/\\//", "\\", $attachment).'"';
	}
	if ($sender) $commandline.=' -replyto "'.blat_escape($sender).'"';
	if ($bcc) $commandline.=' -bcc '.$bcc;
	
	$commandline.=' 2>&1 >"'.$history_dir.'/output.txt"';
	
	//TEST:
	#echo "<hr><pre>".htmlentities(preg_replace('/\|/', "\n", $commandline))."</pre><hr>";
	shell_exec($commandline);
	
	dbio_UPDATE("core_mails", "id=$id", array("sent"=>time()));
}

function blat_escape($text){
	$text=preg_replace('/"/', "''", $text);// '\"' gab Probleme mit ">"
	$text=preg_replace('/&/', '^&', $text);//Windows-Kommandozeilen-Escape
	$text=preg_replace("/\n/", '|', $text);
	return $text;
}

?>