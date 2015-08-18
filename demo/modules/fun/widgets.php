<?php

include_once ROOT_HDD_CORE.'/core/classes/widget.php';

class widget_fun_widget1 extends widget{
	
	//Default-Position:
	var $pos_left=300,$pos_top=350;
	
	function __construct(){
		parent::__construct("widget1", "Fun");
	}
	
	function getContent(){
		include_once ROOT_HDD_CORE.'/demo/modules/fun/fun.php';
		$html=fun_sprichwortgenerator();
		return $html;
	}
	
}

?>