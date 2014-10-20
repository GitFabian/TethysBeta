<?php
if(!defined('USER_ADMIN')||!USER_ADMIN){echo"Keine Berechtigung!";exit;}

if ($version<1){
}

//=================================================================================================
dbio_query("UPDATE `core_meta_dbversion` SET `version` = '1' WHERE `modul_uc` = 'TETHYS';");
//=================================================================================================
?>