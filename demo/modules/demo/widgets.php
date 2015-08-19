<?php

include_once ROOT_HDD_CORE.'/core/classes/widget.php';

class widget_demo_widget1 extends widget{
	
	//Default-Position:
	var $pos_left=300,$pos_top=400;
	
	function __construct(){
		parent::__construct("widget1", "Demo-Widget");
	}
	
	function getContent(){
		$html=html_div(format_Wochentag_Uhrzeit(),"datum");
		return $html;
	}
	
}

?>