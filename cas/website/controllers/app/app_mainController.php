<?php

/**
 * 
 * this _main controller set the theme for every other controller within this folder
 * the name of this main controller should be foldername_mainController
 * @author momon
 *
 */
class app_mainController extends Spine_SuperController implements Spine_MainInterface
{
	
	public function main()
	{
		//check if session is set, if not go to user/login/
		auth::getInstance()->checkAuth('user/login/');
		
		$params = array(
							'description'	=>	'Counselors Aid System'
		);
		
		$this->renderGlobalScript('fullcalendar', 'plugins/fullcalendar/fullcalendar.min.js');
		$this->renderGlobalScript('gcal', 'plugins/fullcalendar/gcal.js');
		$this->renderGlobalScript('gcal', 'plugins/fullcalendar/jquery-ui.custom.min.js');
		$this->renderStyleSheet('fullcalendar', 'plugins/fullcalendar/fullcalendar.css');
		$this->renderStyleSheet('calendar', SITE.'/views/panel/css/calendar.css');
		
		$this->renderLocalScript('local_bottom_script','panel/js/calendar_top_script.js');
		
		//$this->renderTemplate('main_content', 'panel/main', $params);
	}
	
	public function end()
	{
	}
	
}