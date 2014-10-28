<?php

function init_settings(){
	$query_settings=dbio_SELECT("core_settings","`user` IS NULL OR `user`=".USER_ID);
	foreach ($query_settings as $setting) {
		setting_set($setting);
	}
	
	define('CFG_TITLE',setting_get(null,'CFG_TITLE'));
	define('CFG_HOME_TITLE',setting_get(null,'CFG_HOME_TITLE'));
	define('CFG_HOME_URL',setting_get(null,'CFG_HOME_URL'));
	define('CFG_HOME_LABEL',setting_get(null,'CFG_HOME_LABEL'));
	define('CFG_SKIN',trim(setting_get(null,'CFG_SKIN')));
	define('CFG_CSS_VERSION',setting_get(null,'CFG_CSS_VERSION'));
	define('CFG_MODULES',setting_get(null,'CFG_MODULES'));
}

/**
 * Core Setting
 */
function setting_value($key){
	return setting_get(null, $key);
}

function setting_get($modul,$key){
	global $settings;
	if (!$modul) $modul="-CORE-";
	if (!isset($settings[$modul])||!isset($settings[$modul][$key])) return setting_get_default($modul,$key);
	return $settings[$modul][$key];
}

function setting_get_default($modul,$key){
	if ($modul=='-CORE-'){
		$r=get_default_setting($key);
		if ($r===null){
			if ($key=='CFG_TITLE') return "MyTethys";
			if ($key=='CFG_HOME_TITLE') return "Startseite";
			if ($key=='CFG_HOME_URL') return "";
			if ($key=='CFG_HOME_LABEL') return "Start";
			if ($key=='CFG_SKIN') return "demo";
			if ($key=='CFG_CSS_VERSION') return "";
			if ($key=='CFG_MODULES') return "tethys,demo";
			if ($key=='SET_PGSEL_SETTINGS') return "core";
			if ($key=='SET_PGSEL_USERSETS') return "core";
			if ($key=='HM_ICONS') return "0";
			if ($key=='HM_TEXT') return "1";
			if ($key=='DEPRECATED_HMLICLASS') return "0";
			if ($key=='LOGON_MSG') return "Benutzername = Vorname<br>Account beantragen in Büro 321";
			if ($key=='CFG_EDIT_NICK') return "0";
			if (USER_ADMIN) echo "Kein Default-Value für \"$key\"! /core/settings.php:48";
		}
		return $r;
	}else{
		global $modules;
		if (!isset($modules[$modul])){
			if (USER_ADMIN) echo("Modul \"$modul\" nicht installiert!"."<div class=\"entwickler\">".backtrace_to_html(debug_backtrace())."</div>");
			return null;
		}
		return $modules[$modul]->get_default_setting($key);
	}
}

function setting_get_user($modul,$key){
	global $user_settings;
	if (!$modul) $modul="-CORE-";
	if (!isset($user_settings[$modul])||!isset($user_settings[$modul][$key])) return setting_get_default($modul,$key);
	return $user_settings[$modul][$key];
}

function setting_set($setting){
	global $settings, $user_settings;
	$modul=$setting['modul'];
	if (!$modul) $modul="-CORE-";
	$user_id=$setting['user'];
	if ($user_id){
		if (!isset($user_settings[$modul])) $user_settings[$modul]=array();
		$user_settings[$modul][$setting['key']]=$setting['value'];
	}else{
		if (!isset($settings[$modul])) $settings[$modul]=array();
		$settings[$modul][$setting['key']]=$setting['value'];
	}
}

function setting_create($key,$modul,$user,$value){
	return array(
			"key"=>$key,
			"modul"=>$modul,
			"user"=>$user,
			"value"=>$value,
	);
}

function setting_save($modul,$key,$value,$user_specific){
	$has_changed=true;
	global $settings, $user_settings;
	$user=($user_specific?USER_ID:null);
	if ($value===null) $value="";
	$setting=setting_create($key, $modul, $user, $value);
	$where_modul=($modul?"`modul`='$modul'":"`modul` IS NULL");
	$where_user=($user_specific?"`user`=".USER_ID:"`user` IS NULL");
	if (!$modul) $modul="-CORE-";
	if ($user){
		$update=isset($user_settings[$modul])&&isset($user_settings[$modul][$key]);
		$oldval=($update?$user_settings[$modul][$key]:null);
	}else{
		$update=isset($settings[$modul])&&isset($settings[$modul][$key]);
		$oldval=($update?$settings[$modul][$key]:null);
	}
	if ($update){
		if ($value!=$oldval)
			dbio_UPDATE("core_settings", "`key`='$key' AND $where_modul AND $where_user", array("value"=>$value));
		else
			$has_changed=false;
	}else{
		dbio_INSERT("core_settings", $setting);
	}
	setting_set($setting);
	return $has_changed;
}

?>