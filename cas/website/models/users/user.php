<?php

/**
 * 
 * @author Raymond
 *
 */

class user extends Spine_SuperModel
{
	private $db_instance;
	public $table_name	=	'users';
	
	public function __construct()
	{
		$this->db_instance	=	new Db(); //get db instance
	}
	
	//----------------------------------------------------------------------------------------
	
	/**
	 * 
	 * Fetches the data of the User in the database
	 * @param array $params - array of data that will be inserted
	 */
	
	public function select ( $params )
	{
		$conditions	=	'';
		
		foreach ($params as $index => $value)
		{
			$conditions	.=	empty($conditions)? $index.' = :'.$index : ' AND '.$index.' = :'.$index;
		}
		
		return	$this->db_instance->query("SELECT * FROM ".$this->table_name." WHERE $conditions", $params);
	}
	
	//----------------------------------------------------------------------------------------
	
	/**
	 * 
	 * Handles insertion of data in the database
	 * @param array $params - array of data that will be inserted
	 */
	
	public function insert ($params = array())
	{
		$fields	=	'';
		$values	=	'';
		
		foreach ($params as $index => $value)
		{
			$fields	.=	empty ($fields)?$index:', '.$index;
			$values	.=	empty ($values)?':'.$index:', :'.$index;
		}
		
		$fields	=	"($fields)";
		$values	=	"($values)";
		
		$this->db_instance->query("INSERT INTO ".$this->table_name." $fields VALUES $values", $params);
		return	$this->db_instance->rowId();
	}
	
	//----------------------------------------------------------------------------------------
	
	/**
	 * 
	 * Method that interacts with the database during login
	 * @param array $params - login credentials
	 */
	
	public function login ($params = array())
	{
		$conditions	=	'';
		
		foreach ($params as $index => $value)
		{
			$conditions	.=	empty($conditions)? $index.' = :'.$index : ' AND '.$index.' = :'.$index;
		}
		
		
		return	$this->db_instance->query("SELECT user_id, role FROM ".$this->table_name." WHERE $conditions", $params);
	}
	
	//----------------------------------------------------------------------------------------
	
	public function userInstance ()
	{
	}
	
}