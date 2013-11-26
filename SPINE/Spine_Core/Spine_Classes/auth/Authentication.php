<?php

/**
 * This is a simple auth class
 * that uses singleton pattern
 * 
 * requires that session is already started
 * requires an instance of session class
 * 
 * @author momon
 *
 */

class Auth
{
	private static $instance; //holds the instance of itself
	
	final private function __construct() //final private so it cannot be invoked outside the class
	{
		session_start(); //start Session
	}
	
	private function __clone() //disables cloning
	{
		die('Sorry not for cloning.');
	}
	
	public static function getInstance() //the only entry point of the class
	{
		if (self::$instance === NULL)
		{
			self::$instance	=	new self();
		}
		return self::$instance;
	}
	
	public function storeCredentials(array $credentials	=	array(), $redirect_url) //stores the users credentials
	{
		$credentials_for_hash	=	"";
		if (is_array($credentials))
		{
			foreach ($credentials as $index	=> $value)
			{
				Spine_SessionRegistry::setSession('auth', $index, $value);
				$credentials_for_hash	.=	$value;
			}
		}
		Spine_SessionRegistry::setSession('auth', 'spine_hash_key', md5($credentials_for_hash.HASH_KEY));
		$this->redirect($redirect_url);
		exit();
	}
	
	public function checkAuth($redirect_url) //checks if access is authorized
	{
		if (Spine_SessionRegistry::getSession('auth', 'spine_hash_key'))
		{
			return TRUE;
		}
		else
		{
			$this->redirect($redirect_url);
		}
	}
	
	public function verifyCredentials( $credentials = array() )
	{
		foreach ( $credentials as $index => $value )
		{
			if ( Spine_SessionRegistry::getSession('auth', $index) != $value )
				return FALSE;
		}
		
		return TRUE;
	}
	
	public function redirect($redirect_url	=	'')
	{
		header ('location: /'.$redirect_url);
		exit();
	}
	
	public function flush($redirect_url	=	'')
	{
		Spine_SessionRegistry::unsetSession('auth');
		header('location: /'.$redirect_url);
	}
	
	public function retrieveCredentials()
	{
		return	Spine_SessionRegistry::getSession('auth');
	}
}