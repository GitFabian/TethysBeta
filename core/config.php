<?php

$query_cfg=dbio_SELECT("core_config","1","phpname,value");

if ($query_cfg){
	foreach ($query_cfg as $cfg) {
		define($cfg['phpname'],$cfg['value']);
	}
}else{
	define('CFG_TITLE', 'Tethys');
	define('CFG_SKIN', 'demo');
}

?>