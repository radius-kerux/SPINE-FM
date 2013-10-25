<?php

class counselor_mainController extends Spine_SuperController
{
	public function main ()
	{
		//validate user role
		$role		=	Spine_SessionRegistry::getSession('auth', 'role');
		$user_id	=	isset($_GET['id'])?$_GET['id']:0;
		
		if ( !Auth::getInstance()->verifyCredentials(array( 'user_id'	=>	$user_id)) )
			header ( 'location: /user/logout' );
			
		$params		=	array();
		$this->renderTemplate('main_content', 'panel/counselor/counselor_main', $params);
		$this->renderTemplate('header', '', $params);
	}
	
	public function end ()
	{
	}
}