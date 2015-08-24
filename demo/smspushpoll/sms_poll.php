<?php

$polldir="D:\\SMS-POLL";
$age_to_delete=3600/*Seconds*/;

$time_to_delete=time()-$age_to_delete;
$messages_from_here=request_value("from",$time_to_delete);
$files=array();
foreach (scandir($polldir) as $file){
	if (preg_match("/\\.txt$/", $file)){
		$sms_file=file_get_contents($polldir."\\".$file);
		$sms=json_decode($sms_file);
		$time=$sms->time;
		$id=$sms->id;
		if($time<$time_to_delete){
			unlink($polldir."\\".$file);
			continue;
		}
		if($time>$messages_from_here){
			#print_r($sms);
			$files[$id]=$sms;
		}
	}
}

echo json_encode($files);

function request_value($key,$else=null){
	if (isset($_REQUEST[$key])) return $_REQUEST[$key];
	return $else;
}

?>