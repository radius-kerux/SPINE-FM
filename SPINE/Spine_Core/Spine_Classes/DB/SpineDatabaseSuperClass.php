<?php

class Spine_DB
{
	
	public static function connection()
	{
		try
		{
			return new PDO('mysql:host='.DATABASE_HOSTNAME.';dbname='.DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
		}
		catch (PDOException $e)
		{
			print('Could not connect to the Database.');
			exit();
		}
	}
	
}