<?php

class Spine_RequestOverride
{

	public function analyzeRequest()
	{
		include	SPINE_SYS.DS.'SpineDefaultOverrides'.DS.'OverrideAbstractClass.php';
		include	SPINE_SYS.DS.'SpineDefaultOverrides'.DS.'DefaultOverride.php';
		
		$has_user_defined_override	=	FALSE;
		
		$default_override	=	new Spine_DefaultOverride();
		$overrides_array	=	$default_override->getOverrideArray();
		
		if (file_exists(SITE.DS.'override.php'))
		{
			include SITE.DS.'override.php';
			$has_user_defined_override	=	TRUE;
			$user_override	=	new override();
			$user_overrides_array	=	$user_override->getOverrideArray();
			$overrides_array	=	array_merge($overrides_array, $user_overrides_array);
		}
		
		$original_uri_path_array	=	Spine_GlobalRegistry::getRegistryValue('request', 'original_uri_path_array');
		
		if (!empty($original_uri_path_array))
		{
			$override_mark	=	$original_uri_path_array[0];

			if (in_array($override_mark, array_keys($overrides_array)))
			{
				$override_info_array	=	$overrides_array[$override_mark];
				
				if (method_exists($user_override, $override_info_array['call_back']))
				{
					if (isset($override_info_array['parameters']))
					{
						$user_override->$override_info_array['call_back']($override_info_array['parameters']);
					}
					else
					{
						@$user_override->$override_info_array['call_back']();
					}
				}
				elseif (method_exists($default_override, $override_info_array['call_back']))
				{
					if (isset($override_info_array['parameters']))
					{
						$default_override->$override_info_array['call_back']($override_info_array['parameters']);
					}
					else
					{
						@$default_override->$override_info_array['call_back']();
					}
				}
				else 
					return 0;
				
				if (isset($override_info_array['exit']))
				{
					if ($override_info_array['exit'])
						exit();
				}
				
				unset($original_uri_path_array[0]);
				$original_uri_path_array	=	array_values($original_uri_path_array);
				$uri_path_array	=	$original_uri_path_array;
				//var_dump($original_uri_path_array);die;
				Spine_GlobalRegistry::register('request', 'uri_path_array', $uri_path_array);
			}
		}
		else
			return 0;
	}
}