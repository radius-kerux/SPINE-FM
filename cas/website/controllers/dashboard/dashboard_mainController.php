<?php

/**
 * 
 * this _main controller set the theme for every other controller within this folder
 * the name of this main controller should be foldername_mainController
 * @author momon
 *
 */
class dashboard_mainController extends Spine_SuperController implements Spine_MainInterface
{
	
	public function main()
	{
		//check if session is set
		auth::getInstance()->checkAuth('user/login/');
		$role	=	Spine_SessionRegistry::getSession('auth', 'role');
		
		if ( $role != 1 )
		{
			header ('location: /app/');
			exit;
		}
		
		$params = array(
							'description'	=>	'Counselors Aid System'
		);
		
		$this->renderTemplate('main_content', 'home/main', $params);
	}
	
	public function end()
	{
	}
	
}