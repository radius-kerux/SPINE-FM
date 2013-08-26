<?php

class Spine_Request
{
	private $uri;
	
	public function requestHandler()
	{
		$this->uri = parse_url($_SERVER['REQUEST_URI']);
		$this->uri['path'] = rtrim(ltrim($this->uri['path'], '/'), '/');
		Spine_GlobalRegistry::register('request', 'uri_path', $this->uri['path']);
		Spine_GlobalRegistry::register('request', 'original_uri_path_array', explode('/', $this->uri['path']));
		Spine_GlobalRegistry::register('request', 'uri_path_array', $this->constructRequest(explode('/', $this->uri['path'])));
		
		/*	if (($this->uri['path'] !== '') && ($this->uri['path'] !== '/'))
		{
			$this->uri_path_array = explode('/', rtrim(ltrim($this->uri['path'], '/'), '/'));
		}
		//$this->analyzeUriPath();
	*/}
	/**
	 * constructs request based on user defined configuration
	 * @param Array $path - contains the exploded URL as requested by the user
	 */
	private function constructRequest($path = FALSE)
	{
		$new_path = array();
		$filename	=	SITE.DS.'routes.php';
		//var_dump($path);
		if (!empty($path) && ($path[0] !== ""))
		{
			if	(file_exists($filename))
			{
				include $filename;
				
				if (isset($routes))
					Spine_GlobalRegistry::register('route', 'user_defined_routing', $routes);
				else
					die('$routes is not set in '.$filename);
				
				if ($routes !== FALSE)
				{	
					$flag	=	TRUE;	//flag signals if from that point onwards the path will use folder name
					foreach ($path as $index => $route)
					{
						if ($flag)
						{
							if (isset($routes[$route]))
							{
								$routes				=	$routes[$route];
								$new_path[$index]	=	$routes['_name'];
							}
							else
							{
								Spine_GlobalRegistry::register('route', 'params_start_at', $index); 
								$new_path[$index]	=	$route;
								$flag				=	FALSE;
							}
						}
						else
						{
							$new_path[$index]	=	$route;
						}
					}
				}
				else
					$new_path	=	$path;
			}
			else
			{
				$new_path	=	$path;
			}
			return $new_path;
		}
		else
		{
			if (file_exists($filename))
			{
				include $filename;
				
				if (isset($routes))
				{
					if ($routes !== FALSE)
					{
						$new_path[0]	=	$routes['_default'];
						return $this->constructRequest($new_path);
					}
					else
						return $path;
				}
				else
					die('$routes is not set in '.$filename);
			}
		}
	}
	
	private function traverseRoute($route, $routes)
	{
		if (isset($routes[$route]))
		{
			$new_route	=	$routes[$route];
			
		}
	}
		
	public function analyzeUri()
	{
		
	}
	
}