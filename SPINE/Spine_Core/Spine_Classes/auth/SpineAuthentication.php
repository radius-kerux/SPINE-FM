<?php

class Spine_Auth
{
	public static function isUserExists()
	{
		if (isset($_SESSION['spine::user']))
			return true;
		else
			return false;
	}
	
	public static function setUserId()
	{
		$_SESSION['spine::user']['user_id'] = md5(session_id().md5(SITE));
		$_SESSION['spine::user']['user_agent'] = md5($_SERVER['HTTP_USER_AGENT']);
	}
	
	public static function setUserInfo($value)
	{
		$_SESSION['spine::user']['user_info'] = $value;
	}
	
	public static function getUserInfo()
	{
		if (isset($_SESSION['spine::user']['user_info']))
			return $_SESSION['spine::user']['user_info'];
		else
			return false;
	}
	
	public static function unsetUser()
	{
		if (isset($_SESSION['spine::user']))
			unset($_SESSION['spine::user']);
	}
	
	public static function isUserValid()
	{
		if (($_SESSION['spine::user']['user_id'] == md5(session_id().md5(SITE))) 
		&& ($_SESSION['spine::user']['user_agent'] == md5($_SERVER['HTTP_USER_AGENT'])))
		{
			return true;
		}
		else 
			return false;
	}
	
}