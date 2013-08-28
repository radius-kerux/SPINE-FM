<?php

class Spine_SuperController extends Spine_Master
{
	public $spine_authenticate = false;
	public $spine_use_main_controller = true;
	
	protected function renderTemplate($index = 'main_phtml', $value = 'main/main', $params = "")
	{
		$value = SITE.'/views/'.$value.'.template.phtml';
		Spine_GlobalRegistry::register('templates', $index, $value);
		
		if (!(empty($params)))
		{
			$parameters_array = Spine_GlobalRegistry::getRegistryValue('response', 'spine::template_parameters');

			if ($parameters_array)
			{
				foreach($parameters_array as $index => $value) //compiles the values of pervious parameters with the new one
				{
					//overwrites the values of old indexes with new ones
					if (!isset($params[$index])) 
					{
						$params[$index]	=	$value;
					}
				}
			}
			Spine_GlobalRegistry::register('response', 'spine::template_parameters', $params);
		}
	}
	
	protected function renderStyleSheet($index = 'global_stylesheet', $value = 'main/main.css')
	{
		//$index = 'global_stylesheet'; 
		$value = $value;
		Spine_GlobalRegistry::register('stylesheets', $index, $value);
	}
	
	protected function renderLocalScript($index = 'local_bottom_script', $value = 'main/local_script.js')
	{
		//todo
		$values_array = Spine_GlobalRegistry::getRegistryValue('local_scripts', $index);
		if ($values_array !== false)
		{
			array_push($values_array, SITE.'/views/'.$value);
			$values_array = array_filter($values_array);
			Spine_GlobalRegistry::register('local_scripts', $index, $values_array);
		}
		else 
		{
			Spine_GlobalRegistry::register('local_scripts', $index, array(0=>SITE.'/views/'.$value));
		}
		
	}
	
	protected function renderGlobalScript($index = 'global_script', $value = 'main/global_script.js')
	{
		$value = $value;
		Spine_GlobalRegistry::register('global_scripts', $index, $value);		
	}
	
	public function loadSpineClasses($spine_classes_paths = array())
	{
		if (!is_array($spine_classes_paths))
		{
			$temp_string			=	$spine_classes_paths;
			$spine_classes_paths	=	array();
			$spine_classes_paths[0] = $temp_string;
		}
			
		foreach ($spine_classes_paths as $spine_class_path)
		{
			include_once SPINE_CLASSES.DS.$spine_class_path.'.php';
		}
	}
	
	public function loadModel($model_class_path = '')
	{
		if (file_exists(SITE.DS.'models'.DS.$model_class_path.'.php'))
		{
			include_once SITE.DS.'models'.DS.$model_class_path.'.php';
 		}
	}
	
	public function loadModule($module_class_path = '')
	{
		if (file_exists(SITE.DS.'modules'.DS.$module_class_path.'.php'))
		{
			include_once SITE.DS.'modules'.DS.$module_class_path.'.php';
 		}
	}
	
	public function authenticate()
	{
		if ($this->spine_authenticate)
		{
			if (!Spine_Auth::isUserExists())
				header('location: '.$this->spine_authenticate);
		}
		else
			return false;
	}
	
	public function doAuth($data	=	array())
	{
		require_once SPINE_CLASSES.DS.'auth'.DS.'Authentication.php';
		$database_object	=	$data['db_object'];
		$redirect_url		=	$data['redirect_url'];
	}
	
	protected function cacheOutput($id = '')
	{
		$controller	=	Spine_GlobalRegistry::getRegistryValue('route', 'controller');
		$method		=	Spine_GlobalRegistry::getRegistryValue('route', 'method');
		$filename	=	SITE.'/data/cache/templates/'.sha1($controller.$method.$id).'.phtml';
		
		if (file_exists($filename))
		{
			ob_start();
			include $filename;
			ob_end_flush();
			die();
		}
		else
		{
			Spine_GlobalRegistry::register('response', 'cache_final_template', TRUE);
			Spine_GlobalRegistry::register('response', 'cache_id', $id);
		}
	}
	
	/*
	 * Actions
	 */
	
	public function indexAction(){}
	
	public function _404Action()
	{
		echo "<h1>404 Page not found!</h1>";
		die;
	}
	
	public function main(){}
	public function end(){}
}