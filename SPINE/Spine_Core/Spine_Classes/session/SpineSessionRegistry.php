<?php

class Spine_SessionRegistry extends Spine_SessionClass
{
	private $session_array;
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public static function isSessionExists($use, $index)
	{
		if (isset($_SESSION[session_id()][$use][$index]))
			return true;
		else
			return false;
	}
	
	public static function setSession($use, $index, $value)
	{
		$_SESSION[session_id()][$use][$index] = $value;
	}
	
	public static function getSession($use, $index = NULL)
	{
		if (is_null($index))
			if (isset($_SESSION[session_id()][$use]))
				return $_SESSION[session_id()][$use];
				
		if (isset($_SESSION[session_id()][$use][$index]))
			return $_SESSION[session_id()][$use][$index];
			
		return FALSE;
	}
	
	public static function unsetSession($use, $index = NULL)
	{
		if ($index	===	NULL)
			unset($_SESSION[session_id()][$use]);
		else
			unset($_SESSION[session_id()][$use][$index]);
	}
}