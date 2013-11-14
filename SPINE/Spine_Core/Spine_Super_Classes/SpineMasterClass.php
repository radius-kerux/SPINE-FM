<?php

/**
 * 
 * Enter description here ...
 * @author Raymond Baldonado
 *
 */

class Spine_Master
{
	protected function loadModel($model_class_path = '')
	{
		if (file_exists(SITE.DS.'models'.DS.$model_class_path.'.php'))
		{
			include_once SITE.DS.'models'.DS.$model_class_path.'.php';
			return TRUE;
 		}
 		
 		return FALSE;
	}
	
	protected function getRouteController()
	{
		return Spine_GlobalRegistry::getRegistryValue('route', 'controller');
	}
	
	protected function getRoutedAction()
	{
		return Spine_GlobalRegistry::getRegistryValue('route', 'method');
	}
	
	protected function getParameters()
	{
		$parameters_array = Spine_GlobalRegistry::getRegistryValue('route', 'parameters_array');
		if (isset($parameters_array))
			return $parameters_array;
		else
			return false;
	}
	
	protected function getParametersPair($parameter)
	{
		$parameters_array = Spine_GlobalRegistry::getRegistryValue('route', 'parameters_array');
		
		if (isset($parameters_array))
		{
			if (in_array($parameter, $parameters_array))
			{
				$index = array_search($parameter, $parameters_array);
				return isset($parameters_array[$index+1])?$parameters_array[$index+1]:false;
			}
			else
				return false;
		}
	}
	
	protected function getRequestUriPath()
	{
		return Spine_GlobalRegistry::getRegistryValue('request', 'uri_path');
	}
	
	protected function getRequestUriPathArray()
	{
		return Spine_GlobalRegistry::getRegistryValue('request', 'uri_path_array');
	}

	protected function getRequestOriginalUriPathArray()
	{
		return Spine_GlobalRegistry::getRegistryValue('request', 'original_uri_path_array');
	}
	
	public function checkCache($id)
	{
		$filename	=	SITE.DS.'data'.DS.'cache'.DS.'query'.DS.md5($id);
		if (file_exists($filename))
		{
			return unserialize(file_get_contents($filename));
		}
		else
			return FALSE;
	}
	
	public function cache($id, $data)
	{
		$filename	=	SITE.DS.'data'.DS.'cache'.DS.'query'.DS.md5($id);
		$data		=	serialize($data);
		@file_put_contents($filename, $data);
		
		if (file_exists($filename))
			return TRUE;
		else
			return FALSE;
	}
	
	public function unsetCache($id)
	{
		$filename	=	SITE.DS.'data'.DS.'cache'.DS.'query'.DS.md5($id);
		
		if (file_exists($filename))
		{
			unlink($filename);
			return TRUE;
		}
		
		return FALSE;
	}
	
	public function flushCache()
	{
		//remove the whole cache/query folder  
	}
}