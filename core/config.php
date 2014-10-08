<?php
//TODO:Integrieren in settings.php
$query_cfg=dbio_SELECT("core_config","1","phpname,value");

if ($query_cfg){
	foreach ($query_cfg as $cfg) {
		define($cfg['phpname'],$cfg['value']);
	}
}else{
	define('CFG_HOME_LABEL','Start');
	define('CFG_HOME_TITLE','Startseite');
	define('CFG_MODULES', 'demo,tethys');
	define('CFG_SKIN', 'demo');
	define('CFG_TITLE', 'Tethys');
}

?>