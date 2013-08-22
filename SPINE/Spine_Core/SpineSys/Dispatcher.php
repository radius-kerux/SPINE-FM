<?php
class Spine_Dispatcher
{
	//check overrides
	//dispatch main
	//dispatch 
	// 
	
	public function override($override_array)
	{
		
	}
	
	public function dispatchMainController($main_controller_path = "", $main_controller = MAIN_CONTROLLER)
	{
		if ($main_controller_path == null)
		{
			$main_controller_path = DS.MAIN_CONTROLLER.DS.MAIN_CONTROLLER.'Controller.php';
			$main_controller .= 'Controller';
		}
		else
		{
			$main_controller_path = $main_controller_path.'Controller.php';
			$main_controller = $main_controller.'Controller';
		}
			
		if (file_exists(SITE.DS.'controllers'.$main_controller_path))
		{
			include_once SITE.DS.'controllers'.$main_controller_path;
			
			if (class_exists($main_controller))
			{
				$mainMethod = MAIN_METHOD;
				$endMethod = END_METHOD;
				$main = new $main_controller();

				$this->authenticateDispatch($main);
				
				if (method_exists($main_controller, $mainMethod))
					$main->$mainMethod();
				else 
					die('Tough times, mate. You\'re not following the convention, where is your '.MAIN_METHOD.' method?');
					
				if (method_exists($main_controller, $endMethod))
					$main->$endMethod();
				else 
					die('Tough times, mate. You\'re not following the convention, where is your '.END_METHOD.' method?');
			}
			else
				die('Tough times, mate. You\'re not following the convention, where is your '.MAIN_CONTROLLER.' Controller?');
		}
		
		unset($main);
		
	}
	
	public function dispatchRoute()
	{
		$controller = Spine_GlobalRegistry::getRegistryValue('route', 'controller');
		$method = Spine_GlobalRegistry::getRegistryValue('route', 'method');
		if (class_exists($controller))
		{
			Spine_GlobalRegistry::register('instance', 'dispatched_controller', new $controller);
			
			$accumulated_path = Spine_GlobalRegistry::getRegistryValue('route', 'controller_accumulated_path');
			$accumulated_path = rtrim($accumulated_path, DS);
			$accumulated_path = explode(DS, $accumulated_path);
			
			$temp_accumulated_path = '';
			if (Spine_GlobalRegistry::getRegistryValue('instance', 'dispatched_controller')->spine_use_main_controller == true)
			{
				foreach ($accumulated_path as $path)
				{
					$temp_accumulated_path .= $path.DS;
					$this->dispatchMainController($temp_accumulated_path.$path.'_'.MAIN_CONTROLLER, $path.'_'.MAIN_CONTROLLER);
				}
			}
			
			if (method_exists($controller, $method))
			{
				$this->authenticateDispatch(Spine_GlobalRegistry::getRegistryValue('instance', 'dispatched_controller'));
				Spine_GlobalRegistry::getRegistryValue('instance', 'dispatched_controller')->main();
				Spine_GlobalRegistry::getRegistryValue('instance', 'dispatched_controller')->$method();
				Spine_GlobalRegistry::getRegistryValue('instance', 'dispatched_controller')->end();
			}
			else
			{
				$method = '_404Action';
				Spine_GlobalRegistry::getRegistryValue('instance', 'dispatched_controller')->main();
				Spine_GlobalRegistry::getRegistryValue('instance', 'dispatched_controller')->$method();
				Spine_GlobalRegistry::getRegistryValue('instance', 'dispatched_controller')->end();
			}
		}
		else
		{
			Spine_GlobalRegistry::register('route','controller',DEFAULT_CONTROLLER.'Controller');
			$this->dispatchRoute();
		}
	}
	
	public function authenticateDispatch($instance_of_controller)
	{
		$instance_of_controller->authenticate();			
	}
}