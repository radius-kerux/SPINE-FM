<?php

class appController extends Spine_SuperController
{
	public function main()
	{
		$this->loadModel('users/user');
		//$this->renderTemplate('inner_most', 'home/set');
		$this->control();
	}
	
	/**
	 * 
	 * Controls application access
	 * Role 1	=	Super Admin
	 * Role 2	=	Admin
	 * Role 3	=	counselor
	 */
	
	private function control()
	{
		$credentials					=	array();
		$credentials['role']			=	Spine_SessionRegistry::getSession('auth', 'role');
		$credentials['user_id']			=	Spine_SessionRegistry::getSession('auth', 'user_id');
		$credentials['user_name']		=	Spine_SessionRegistry::getSession('auth', 'user_name');
		//validate
		
		switch ($credentials['role']) 
		{
			case 1:
				header ('location: /dashboard');
			exit();
			
			case 2:
				//validate
			//	echo 'asd';die;
				header ('location: /app/admin?id='.$credentials['user_id']);
			exit();
			
			case 3:
				header ('location: /app/counselor?id='.$credentials['user_id']);
			exit();
			
			default:
				die ('User Control Error');
			break;
		}
	}
	
}