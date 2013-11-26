<?php

/**
 * Spine Super controller provides functionalities to its 
 * children (application controllers)
 * It inherits Spine Master
 * 
 * @author Raymond Dominguez Baldonado
 *
 */

class Spine_SuperController extends Spine_Master
{
	public $spine_authenticate = FALSE; //used in authentication, the default way
	public $spine_use_main_controller = TRUE; //it sets a flag if the user will use specific main controller or not
	
	//------------------------------------------------------------------------------------
	
	/**
	 * 
	 * renders phtml templates in view folder
	 * @param string $index - key of the template in the stack
	 * @param string $value - path of the template inside the views folder 
	 * @param array $params - parameters/data that are needed in the template
	 */
	
	protected function renderTemplate($index = 'main_phtml', $value = 'main/main', $params = "")
	{
		if ( !empty( $value ) )
		{
			$value = 'REMOVE' != $value? SITE.'/views/'.$value.'.template.phtml' : 'REMOVE'; //set the path to views folder
			Spine_GlobalRegistry::register('templates', $index, $value); //registers the template to Spine_GlobalRegistry
		}
		else 
		{
			Spine_GlobalRegistry::removeInRegistry('templates', $index);
		}
		
		if (!(empty($params))) //checks if parameters are provided
		{
			$parameters_array = Spine_GlobalRegistry::getRegistryValue('response', 'spine::template_parameters'); //gets the reqistered parameters in the registry

			if ($parameters_array) 
			{
				foreach($parameters_array as $index => $value) //compiles all the values of pervious parameters with the new one
				{
					//overwrites the values of old indexes with new ones
					if (!isset($params[$index])) 
					{
						$params[$index]	=	$value; 
					}
				}
			}
			Spine_GlobalRegistry::register('response', 'spine::template_parameters', $params); //registers the parameters to the global registry 
		}
	}
	
	//------------------------------------------------------------------------------------
	
	/**
	 * 
	 * Alias of renderTemplate.
	 * Renders phtml templates in view folder
	 * @param string $index - key of the template in the stack
	 * @param string $value - path of the template inside the views folder 
	 * @param array $params - parameters/data that are needed in the template
	 */
	
	protected function displayPhtml($index = 'main_phtml', $value = 'main/main', $params = "")
	{
		$this->renderTemplate($index, $value, $params);
	}
	
	//------------------------------------------------------------------------------------
	
	/**
	 * 
	 * Renders stylesheets to be compiled in the marked templates
	 * @param string $index - index/mark-up in the template where the stylesheets will be located
	 * @param string $value - path of css
	 */
	
	protected function renderStyleSheet($index = 'global_stylesheet', $value = 'main/main.css')
	{
		//$index = 'global_stylesheet'; 
		//$value = $value; 
		Spine_GlobalRegistry::register('stylesheets', $index, $value); //register the stylesheet to global registry
	}
	
	//------------------------------------------------------------------------------------
	
	protected function includeStyleSheet($index = 'global_stylesheet', $value = 'main/main.css')
	{
		$array_of_stylesheets	=	Spine_GlobalRegistry::getRegistryValue('external_stylesheets', $index);
		
		if ($array_of_stylesheets !== FALSE)
		{
			array_push($array_of_stylesheets, $value);
			$array_of_stylesheets = array_filter($array_of_stylesheets);
			Spine_GlobalRegistry::register('external_stylesheets', $index, $array_of_stylesheets);
		}
		else 
		{
			Spine_GlobalRegistry::register('external_stylesheets', $index, array( 0 => $value ));
		}
	}
	
	//------------------------------------------------------------------------------------
	
	/**
	 * 
	 * @param string $index - index/mark inside the templates where the scripts will be embedded
	 * @param string $value
	 */
	
	protected function renderExternalScript($index = 'local_bottom_script', $value)
	{
		//todo
		$values_array = Spine_GlobalRegistry::getRegistryValue('external_scripts', $index); //gets the registered scripts in the global registry
		
		if ($values_array!==FALSE) //checks if the registry is NOT empty
		{
			//$values_array[$stack_name]	=	SITE.'/views/'.$value; 
			array_push($values_array, $value); //adds the script to the array
			$values_array = array_filter($values_array); //removes empty indexes
			Spine_GlobalRegistry::register('external_scripts', $index, $values_array); //registers the array of values again
		}
		else //if the script registry is empty
		{
			Spine_GlobalRegistry::register('external_scripts', $index, array( 0 => $value )); //set the values provided as the first item in the registry
		}
	}
	
	//------------------------------------------------------------------------------------
	
	/**
	 * renderExternalScript - Alias
	 * @param string $index - index/mark inside the templates where the scripts will be embedded
	 * @param string $value
	 */
	
	protected function includeExternalScript($index = 'local_bottom_script', $value = 'main/local_script.js')
	{
		$this->renderExternalScript($index, $value);
	}
	
	//------------------------------------------------------------------------------------
	
	/**
	 * 
	 * Renders local scripts to be compiled in the marked templates
	 * local scripts are those that are directly embedded in phtml templates
	 * within <script></script> tags
	 * @param string $index - index/mark inside the templates where the scripts will be embedded
	 * @param string $value
	 */
	
	protected function renderLocalScript($index = 'local_bottom_script', $value = 'main/local_script.js')
	{
		//todo
		$values_array = Spine_GlobalRegistry::getRegistryValue('local_scripts', $index); //gets the registered scripts in the global registry
		if ($values_array !== false) //checks if the registry is NOT empty
		{
			array_push($values_array, SITE.'/views/'.$value); //adds the script to the array
			$values_array = array_filter($values_array); //removes empty indexes
			Spine_GlobalRegistry::register('local_scripts', $index, $values_array); //registers the array of values again
		}
		else //if the script registry is empty
		{
			Spine_GlobalRegistry::register('local_scripts', $index, array( 0 => SITE.'/views/'.$value )); //set the values provided as the first item in the registry
		}
	}
	
	//------------------------------------------------------------------------------------
	
	protected function includeInPageScript($index = 'local_bottom_script', $value = 'main/local_script.js')
	{
		$this->renderLocalScript($index, $value);
	}
	
	//------------------------------------------------------------------------------------
	
	/**
	 * 
	 * Renders global scripts in the marked templates
	 * @param string $index - index/mark inside the templates
	 * @param string $value - path of the scripts 
	 */
	
	protected function renderGlobalScript($index, $value = 'main/global_script.js')
	{
		Spine_GlobalRegistry::register('global_scripts', $index, $value); //registers the scripts	
	}
	
	//------------------------------------------------------------------------------------
	
	protected function includeGlobalScript($index, $value = 'main/global_script.js')
	{
		$this->includeGlobalScript($index, $value); //registers the scripts	
	}
	
	//------------------------------------------------------------------------------------
	
	/**
	 * 
	 * loads spine classes (tools inside the spine core)
	 * @param array $spine_classes_paths - paths of classes inside spine core
	 */
	
	public function loadSpineClasses($spine_classes_paths = array())
	{
		if (!is_array($spine_classes_paths)) //checks if $spine_classes_paths is NOT an array
		{
			$temp_string			=	$spine_classes_paths; //sets the value to a temp variable
			$spine_classes_paths	=	array(); // converts $spine_classes_paths to array
			$spine_classes_paths[0] = $temp_string; //set the temporary variable as the first value of the array
		}
			
		foreach ($spine_classes_paths as $spine_class_path) //traverse thru the array of paths
		{
			include_once SPINE_CLASSES.DS.$spine_class_path.'.php'; //include the paths
		}
	}
	
	//------------------------------------------------------------------------------------
	
	/**
	 * 
	 * Loads modules located in the modules folder inside the application 
	 * @param string $module_class_path - path of the module
	 */
	
	public function loadModule($module_class_path = '')
	{
		if (file_exists(SITE.DS.'modules'.DS.$module_class_path.'.php')) //checks if file exists
		{
			include_once SITE.DS.'modules'.DS.$module_class_path.'.php'; //include the specified files
 		}
	}
	
	//------------------------------------------------------------------------------------
	
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
	
	//------------------------------------------------------------------------------------
	
	public function doAuth($data	=	array())
	{
		require_once SPINE_CLASSES.DS.'auth'.DS.'Authentication.php';
		$database_object	=	$data['db_object'];
		$redirect_url		=	$data['redirect_url'];
	}
	
	//------------------------------------------------------------------------------------
	
	/**
	 * 
	 * Stores the output of a block of code for caching
	 * @param string $id - name by which the cached output will be identified
	 * it has to be unique to avoid fetching wrong output
	 */
	
	protected function cacheOutput($id = '')
	{
		$controller	=	Spine_GlobalRegistry::getRegistryValue('route', 'controller'); //gets the invoked controller
		$method		=	Spine_GlobalRegistry::getRegistryValue('route', 'method'); //gets the invoked method
		$filename	=	SITE.'/data/cache/templates/'.sha1($controller.$method.$id).'.phtml'; //set the file path of the cached output
		
		if (file_exists($filename)) //checks if the file exists
		{
			//taken from http://css-tricks.com/snippets/php/intelligent-php-cache-control/
			$lastModified		=	filemtime($filename);
			$ifModifiedSince	=	(isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] : false);
			$etagFile 			=	md5_file($filename);
			$etagHeader			=	(isset($_SERVER['HTTP_IF_NONE_MATCH']) ? trim($_SERVER['HTTP_IF_NONE_MATCH']) : false);
			
			header("Last-Modified: ".gmdate("D, d M Y H:i:s", $lastModified)." GMT");
			header('Cache-Control: public');
			
			if (@strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $lastModified || $etagHeader == $etagFile)
			{
				header("HTTP/1.1 304 Not Modified");
				 exit;
			}
			//------------------------------------------------------------------------------------
			//load for display the cached output if the file already exists
			ob_start();
			include $filename;
			ob_end_flush();
			die();
		}
		else
		{
			//if not, register the cached output to the registry
			Spine_GlobalRegistry::register('response', 'cache_final_template', TRUE);
			Spine_GlobalRegistry::register('response', 'cache_id', $id);
		}
	}
	
	//------------------------------------------------------------------------------------
	
	/**
	 * 
	 * Sets 404 template that will be used 
	 * @param string $template - path of the template
	 */
	
	protected function set404Template($template)
	{
		Spine_GlobalRegistry::register('response', '404_template', $template); //registers the template 
	}
	
	//------------------------------------------------------------------------------------
	
	/*
	 * Actions
	 */
	
	public function indexAction(){}
	
	//------------------------------------------------------------------------------------
	
	public function _404Action()
	{
		$template	=	Spine_GlobalRegistry::getRegistryValue('response', '404_template');
		header('HTTP/1.0 404 Not Found');
		echo $template?$template:"<h1>404 Page not found!</h1>";
		die;
	}
	
	//------------------------------------------------------------------------------------
	
	public function setHeaders($page_headers)
	{
		$page_headers_array	=	Spine_GlobalRegistry::getRegistryValue('response', 'page_headers');
		
		if ($page_headers_array !== FALSE)
		{
			if (is_array($page_headers))
			{
				$page_headers_array	=	array_merge($page_headers_array, $page_headers);
				Spine_GlobalRegistry::register('response', 'page_headers', $page_headers_array);
			}
			else 
			{
				array_push($page_headers_array, $page_headers);
				Spine_GlobalRegistry::register('response', 'page_headers', $page_headers_array);
			}
		}
		else 
		{
			if (is_array($page_headers))
				$page_headers_array	=	array_filter($page_headers);
			else
				$page_headers_array	=	array(0 => $page_headers);
		}
		Spine_GlobalRegistry::register('response', 'page_headers', $page_headers_array);
	}
	
	//------------------------------------------------------------------------------------
	
	public function main(){}
	public function end(){}
}