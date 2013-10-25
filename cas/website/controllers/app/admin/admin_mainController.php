<?php

class admin_mainController extends Spine_SuperController
{
	public function main ()
	{
		//validate user role
		$role	=	Spine_SessionRegistry::getSession('auth', 'role');
		
		if ( $role > 2 )
		{
			header ('location: /user/logout/');
			exit;
		}
	}
	
	public function end ()
	{
		
	}
}