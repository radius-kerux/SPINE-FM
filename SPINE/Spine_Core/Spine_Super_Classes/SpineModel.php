<?php

class Spine_SuperModel extends Spine_Master
{
	//these properties are used for auth class
	protected $spine_user_id;
	protected $spine_user;
	protected $spine_password;
	
	public function __set($property, $value)
	{
		if (property_exists($this, $property))
			$this->{$field}	=	$value;
		else
			return FALSE;
		return TRUE;
	}
	
	public function __get($property)
	{
		if (property_exists($this, $property))
			return $this->{$field};
		else
			return FALSE;
	}
	
	public function __construct()
	{
		include_once SPINE_CLASSES.DS.'DB'.DS.'SpineDatabaseSuperClass.php';
	}
	
	public function SpineLogin()
	{
		return FALSE;
	}
}