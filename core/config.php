<?php

$query_cfg=dbio_SELECT("core_config","1","phpname,value");

foreach ($query_cfg as $cfg) {
	define($cfg['phpname'],$cfg['value']);
}

?>