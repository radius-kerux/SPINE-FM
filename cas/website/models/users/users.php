<?php

class users extends Spine_SuperModel
{
	public function selectApplication($id = 1)
	{
		try 
		{
			$connection	=	Spine_DB::connection();
			$sql	=	"SELECT
							*
						FROM
							applications
						WHERE
							application_id	=	:app_id
						";
			
			$statement	=	$connection->prepare($sql);
			
			$statement->bindParam(':app_id', $id, PDO::PARAM_INT);
			$statement->execute();
			$result	=	$statement->fetch(PDO::FETCH_ASSOC);
			return $result;
		}
		catch (PDOException $e)
		{
			throw new Exception($e);
			die();
		}
	}
}