<?php

class Spine_DefaultOverride extends Spine_OverrideAbstract
{
	protected $overrides_array	=	array(
		'spine_block'		=>	array(
			'call_back'		=>	'routeBlock',
			'exit'			=>	TRUE
		),
		
		'sitemap.xml'		=>	array(
			'call_back'		=>	'displayXml',
			'exit'			=>	TRUE
		),
		
		'sample_override'	=>	array(
			'call_back'		=>	'say',
			'parameters'	=>	array(
				'Radius',
				'Kerux'
			),
			'exit'			=>	TRUE	
		),
		
		'php_info'			=>	array(
			'call_back'		=>	'showPHPInfo',
			'exit'			=>	TRUE	
		),
		
		'spine-gr-diag'		=>	array(
			'call_back'		=>	'showGR'
		),
		
		'_echo'				=>	array(
			'call_back'		=>	'ec'
		),
		
		'_install'			=>	array(
			'call_back'		=>	'install',
			'exit'			=>	TRUE
		),
		'_clearcache'		=>	array(
			'call_back'		=>	'clearCache',
			'exit'			=>	TRUE
		)
	);
	
	public function routeBlock()
	{
		try 
		{
			$uri_path_array	=	Spine_GlobalRegistry::getRegistryValue('request', 'uri_path_array');
		
			unset($uri_path_array[0]);
			$count			=	count($uri_path_array);
			$method			=	$uri_path_array[$count];
			unset($uri_path_array[$count]);
			$uri_path		=	trim(implode('/', $uri_path_array), '/');
			$class			=	$uri_path_array[$count-1];
			
			include SITE.DS.'blocks'.DS.$uri_path.'Block.php';
	
			$class			=	$class.'Block';
			
			$block_class	=	new	$class();
			$block_class->$method();
		}
		catch (Exception $e)
		{ 
			echo $e->getMessage();
		}
		
	}
	
	public function displayXml()
	{
		echo 'a sitemap must be put here';
	}

	public function say($params)
	{
		echo 'HEY, '.$params[0].'---'.$params[1].'!!!';
	}
	
	public function showPHPInfo()
	{
		phpInfo();
	}
}