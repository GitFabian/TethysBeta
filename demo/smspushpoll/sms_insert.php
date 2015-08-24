<?php

$polldir="D:\\SMS-POLL";

$id=uniqid("");
$filename=$polldir."\\".$id.".txt";
$data=json_encode(array(
	"ip"=>$_SERVER["REMOTE_ADDR"],
	"id"=>$id,
	"time"=>time(),
	"recipient"=>request_value("recipient"),
	"message"=>request_value("message"),
	"sender"=>request_value("sender"),
	"pass"=>request_value("pass"),
));
file_put_contents($filename, $data);

function request_value($key,$else=null){
	if (isset($_REQUEST[$key])) return $_REQUEST[$key];
	return $else;
}

?>