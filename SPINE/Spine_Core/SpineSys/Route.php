<?php
class Spine_Route
{
	/**
	 * Name: setUpRoute
	 * Desc: Checks the segments of the URL path one by one then routes the request hierarchically
	 * if a segment is speculatively considered as folder index will point and check the next  
	 * 
	 * var $uriPathArray - the whole path specified in URL registered in Global Registry. Passed by ref
	 * var $index - serves as the needle pointing to each segment recursively
	 * var $accumulated_path - is the path collected consisting of segments that are considered as folders
	 * 
	 * Hierarchical Approach in parsing uri. Still uses 'controller' and 'action' keywords.
	 * Does recursion in routing a request
	 * If folder name is separated by '-' class name of controller should be in camel case
	 * Every assumption is written and overwritten in global registry before dispatching
	 */
	
	public function setUpRoute(&$uriPathArray, $index = 0, $accumulatedPath = "")
	{
		$paramStartsAt = null;
		$routeMethod = '';
		$routeController = '';
		
		/**
		 * Checks if current segment is NOT set. 
		 * If NOT it is probably the end of URL path.
		 * It will assume that default controller is requested at FIRST check
		 * 
		 * */
		
		if (!isset($uriPathArray[$index]))
		{
			include_once SITE.DS.'controllers'.DS.DEFAULT_CONTROLLER.DS.DEFAULT_CONTROLLER.'Controller.php';
			Spine_GlobalRegistry::register('route','controller',DEFAULT_CONTROLLER.'Controller');
			
			/**
			 * Checks if previous segment is set and not empty
			 * if empty, this could mean that only the top domain is specified
			 * so will get default action
			 * 
			 * if is set and not empty will consider the previous segment as an Action method
			 * 
			 */
			
			if (isset($uriPathArray[$index-1]) && ($uriPathArray[$index-1] != ""))
			{
				$routeMethod = $this->aliasHandler($uriPathArray[$index-1]); 
				$routeMethod = $routeMethod.'Action';
				Spine_GlobalRegistry::register('route','method',$uriPathArray[$index-1].'Action');
			}
			else
				Spine_GlobalRegistry::register('route','method',DEFAULT_METHOD.'Action');
		}
		else
		{
			$accumulatedPath .= DS.$uriPathArray[$index];
			$segment = $uriPathArray[$index];
		
			if (isset($uriPathArray[$index+1])) //checks if the next segment is set
			{
				$nextSegment = $uriPathArray[$index+1];
				
				$routeMethod = $this->aliasHandler($nextSegment);
				$routeMethod = $routeMethod.'Action'; //assumes that it is an Action method
				
				if (isset($uriPathArray[$index+2]))
					$paramStartsAt = $index+2;
			}
			else
			{
				$routeMethod = DEFAULT_METHOD.'Action';
			}
			
			if (@file_exists(SITE.DS.'controllers'.$accumulatedPath.DS.$nextSegment.'Controller.php')) //note: sample/sample/controller
			{
				include_once SITE.DS.'controllers'.$accumulatedPath.DS.$nextSegment.'Controller.php';
				
				Spine_GlobalRegistry::register('route', 'controller_accumulated_path', $accumulatedPath.DS);
				
				if (isset($uriPathArray[$index+2]))
				{
					$routeMethod = $this->aliasHandler($uriPathArray[$index+2]);
					$routeMethod = $routeMethod.'Action';
					if (isset($uriPathArray[$index+3]))
						$paramStartsAt = $index+3;
				}
				else
				{
					$routeMethod = DEFAULT_METHOD.'Action';
					//$routeMethod = '_404Action';
				}
				
				$routeController = $this->aliasHandler($nextSegment);
				$routeController = $routeController.'Controller';
				
				if (class_exists($routeController))
				{
					Spine_GlobalRegistry::register('route','controller',$routeController);
					Spine_GlobalRegistry::register('route', 'controller_accumulated_path', $accumulatedPath.DS);
					
					if (method_exists($routeController, $routeMethod))
					{
						Spine_GlobalRegistry::register('route','method',$routeMethod);
					}
					/*elseif (method_exists($nextSegment.'Controller', DEFAULT_METHOD.'Action'))
					{
						Spine_GlobalRegistry::register('route','method',DEFAULT_METHOD.'Action');
						$paramStartsAt = $index+2;
					}*/
					else
						Spine_GlobalRegistry::register('route','method', '_404Action');
						//die('No Indexx is set for '.$nextSegment.'Controller---'.$routeMethod);
				}
			}
			elseif (file_exists(SITE.DS.'controllers'.$accumulatedPath))
			{
				if (isset($uriPathArray[$index+1]))
					$nextSegment = $uriPathArray[$index+1];
				else
					$nextSegment = false;
				
				if (file_exists(SITE.DS.'controllers'.$accumulatedPath.DS.$nextSegment) && $nextSegment)
				{
					$this->setUpRoute($uriPathArray, $index+1, $accumulatedPath);
				}
				elseif (file_exists(SITE.DS.'controllers'.$accumulatedPath.DS.$segment.'Controller.php'))
				{
					include_once SITE.DS.'controllers'.$accumulatedPath.DS.$segment.'Controller.php';
					
					$routeController = $this->aliasHandler($segment);
					$routeController = $routeController.'Controller';
					
					if (class_exists($routeController))
					{
						Spine_GlobalRegistry::register('route','controller',$routeController);
						Spine_GlobalRegistry::register('route', 'controller_accumulated_path', $accumulatedPath.DS);
						
						if (method_exists($routeController , $routeMethod))
						{
							Spine_GlobalRegistry::register('route','method',$routeMethod);
						}
						/*elseif (method_exists($routeController, DEFAULT_METHOD.'Action'))
						{
							Spine_GlobalRegistry::register('route','method',DEFAULT_METHOD.'Action');
						}*/
						else
							Spine_GlobalRegistry::register('route','method', '_404Action');
					}
				}
				else
				{
					$this->setUpRoute($uriPathArray, $index+1, $accumulatedPath); //assumes that the segment is a folder so recursion will take place
				}
			}
			else
			{
				include_once SITE.DS.'controllers'.DS.DEFAULT_CONTROLLER.DS.DEFAULT_CONTROLLER.'Controller.php';
				/*Spine_GlobalRegistry::register('route', 'controller_accumulated_path', $accumulatedPath.DS);
				Spine_GlobalRegistry::register('route','controller',DEFAULT_CONTROLLER.'Controller');
				Spine_GlobalRegistry::register('route','method',DEFAULT_METHOD.'Action');*/
				//to say the page you are looking does not exist
				Spine_GlobalRegistry::register('route', 'controller_accumulated_path', $accumulatedPath.DS);
				Spine_GlobalRegistry::register('route','controller',DEFAULT_CONTROLLER.'Controller');
				Spine_GlobalRegistry::register('route','method','_404Action');
			} 
			Spine_GlobalRegistry::register('route', 'params_start_at', $paramStartsAt); 
		}
	}
	
	//------------------------------------------------------------------------------------
	
	public function setParameters()
	{
		$paramStartsAt = Spine_GlobalRegistry::getRegistryValue('route', 'params_start_at');
		$requestUriPathArray = Spine_GlobalRegistry::getRegistryValue('request', 'uri_path_array');
		$requestUriPathArray = array_slice($requestUriPathArray, $paramStartsAt);
		Spine_GlobalRegistry::register('route', 'parameters_array', $requestUriPathArray);
	}
	
	//------------------------------------------------------------------------------------
	
	public function test()
	{
		var_dump(Spine_GlobalRegistry::getRegistryValue('route', 'controller'));
		var_dump(Spine_GlobalRegistry::getRegistryValue('route', 'method'));
	}
	
	//------------------------------------------------------------------------------------
	
	protected function aliasHandler($string)
	{
		$new_string = '';
		
		foreach (explode('-', $string) as $index => $value)
			if ($index != 0)
				$new_string .= ucfirst($value);
			else
				$new_string .= $value;
		return $new_string;
	}
	
}