<?php

class Spine_GlobalRegistry
{
	/**
	 * registry pattern
	 * designations - indexes: [route - path, controller, method, parameters], [overrides - ], [instance ]
	 * 
	 */
	
	private static $globalRegistry = array();
	
	public static function register($designation = 'overrides', $index = 'default', $value = '__radius__' )
	{
		//if (!isset(self::$globalRegistry[$designation][$index]))
			self::$globalRegistry[$designation][$index] = $value;
	}
	
	public static function getRegistryValue($designation = 'overrides', $index = 'default')
	{
		if (isset(self::$globalRegistry[$designation][$index]))
			return self::$globalRegistry[$designation][$index];
		else
			return false;
	}
	
	public static function removeInRegistry( $designation, $index )
	{
		if ( isset( self::$globalRegistry[$designation][$index] ) )
			unset( self::$globalRegistry[$designation][$index] );
		
		return TRUE;
	}
	
	public static function getDesignationArray($designation = 'overrides')
	{
		if (isset(self::$globalRegistry[$designation]))
			return self::$globalRegistry[$designation];
		else
			return false;
	}
	
	public static function viewRegistryContent()
	{
		echo '<pre>';
		var_dump(self::$globalRegistry);
		echo '</pre>';
		die();
	}
}
