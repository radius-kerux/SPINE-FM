<?php
class userController extends Spine_SuperController
{
	public function main()
	{
		$this->loadModel('users/user');
		$this->loadModel('users/users');
	}
	
	public function indexAction()
	{
	}
	
	public function loginAction()
	{
		if (isset($_POST['login']) && isset($_POST['username']) && isset($_POST['password']))
		{
			$user_data_array	=	$_POST;
			unset($user_data_array['login']);
			
			$user	=	new user();
			$user_data_array['password']	=	$this->hashPassword($user_data_array['password']);
			$user_id	=	$user->login($user_data_array);
			
			$role		=	$user_id[0]['role'];
			$user_id	=	$user_id[0]['user_id'];

			if ($user_id)
			{
				$user_data_array['user_id']	=	$user_id;
				$user_data_array['role']	=	$role;
				
				auth::getInstance()->storeCredentials($user_data_array, 'app/');
			}
			else 
				header ('location: /user/login');
				
			exit();
		}
		else
		{
			$this->displayLoginForm();
		}
	}
	
	public function logoutAction()
	{
		auth::getInstance()->flush('user/login');
	}
	
	public function registerAction ()
	{
		if (isset($_POST['register']))
		{
			$user_data_array	=	$_POST;
			//data validation
			//data register
			
			$user	=	new user();
			$user_data_array['password']	=	$this->hashPassword($user_data_array['password']);
			unset($user_data_array['register']);
			$user->insert($user_data_array);
		}
		else 
		{
			$this->displayRegistrationForm();
		}
	}
	
	private function displayRegistrationForm ($params = array(), $message = "")
	{
		$this->renderTemplate('user_form', 'user/register', $params);
	}
	
	private function displayLoginForm ( $params = array() )
	{
		$this->renderTemplate('user_form', 'user/login', $params);
	}
	
	private function hashPassword ($password)
	{
		return sha1( md5( $password ) );
	}
}