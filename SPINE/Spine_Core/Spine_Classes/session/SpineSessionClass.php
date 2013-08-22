<?php

class Spine_SessionClass
{
	public function __construct()
	{
		if (!file_exists('tmp'))
			mkdir('tmp', 0777);
			
		$this->sessionStart();
	}
	
	public function sessionStart()
	{
		if (!isset($_SESSION[session_id()]))
			$_SESSION[session_id()]	=	array();
		else
		{
			self::setSessionID();
		}
	}
	
	public static function setSessionID()
	{
		session_id(md5($_SERVER['HTTP_USER_AGENT']).session_id());
	}
	
	public static function sessionDestroy()
	{
		session_destroy();
	}
}