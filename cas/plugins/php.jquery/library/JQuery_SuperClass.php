<?	
// Where I put my personal functions for Ajax handling


class JQuery_SuperClass
{
	public static function variabalizePostedData()
	{
		if (isset($_POST['data']))
		{
			$returnedVariables = array();
		
			$fields = explode("&",$_POST['data']);
			foreach($fields as $field)
			{
				$field_key_value = explode("=",$field);
				$key = urldecode($field_key_value[0]);
				$value = urldecode($field_key_value[1]);
				$returnedVariables[$key] = (string) $value;
			}
		return 	$returnedVariables;
		}
		else
		{
			return false;		
		}
	}
}		
?>