<?php

class Spine_FrontController
{
	private $_instanceOfDispatcher;
	private $_instanceOfRequest;
	private $_instanceOfRoute;
	private $_instanceOfResponse;
	private $_instanceOfViewRenderer;
	private $_instanceOfGlobalRegistry;
	
	//------------------------------------------------------------------------------------
	
	private $_userRequest;
	private $_instanceOfController;
	
	//------------------------------------------------------------------------------------
	
	public function __construct()
	{
		include 'SPINE/Spine_Core/SpineSys/Spine_Parameters.php';
		
		include 'SpineDirectoryStructure.php';
		include SPINE_SYS.DS.'GlobalRegistry.php';
		$this->startSession();
		$this->includeSuperClasses();
	}
	
	//------------------------------------------------------------------------------------
	
	public function init()
	{
		$this->initializeSystem(); 
		$this->handleUserRequest();
		$this->handleGlobalOverride();
		$this->loadProjectParameters();
		$this->processUserRequest();
		$this->handleRequestOverride();
		$this->execPredefOverride();
		$this->setUpRouting();
		$this->execPredefOverride();
		$this->dispatch();
		$this->response();
		die();
	}
	
	//------------------------------------------------------------------------------------
	
	public function install()
	{
		include SPINE_CLASSES.DS.'install'.DS.'install.php';
		new install();
	}
	
	//------------------------------------------------------------------------------------
	
	private function includeSuperClasses()
	{
		include SPINE_SUPER_CLASSES.DS.'SpineMasterClass.php';
		include SPINE_SUPER_CLASSES.DS.'SpineController.php';
		include SPINE_SUPER_CLASSES.DS.'SpineModel.php';
		include SPINE_SUPER_CLASSES.DS.'SpineView.php';
		include SPINE_SUPER_CLASSES.DS.'SpineMainInterface.php';
		include SPINE_SUPER_CLASSES.DS.'SpineBlock.php';
	}
	
	//------------------------------------------------------------------------------------
	
	private function initializeSystem()
	{
		include_once SPINE_SYS.DS.'Request.php';
		include_once SPINE_SYS.DS.'RequestOverride.php';
		include_once SPINE_SYS.DS.'RouteAbstractClass.php';
		include_once SPINE_SYS.DS.'Route.php';
		include_once SPINE_SYS.DS.'ViewRenderer.php';
		include_once SPINE_SYS.DS.'Response.php';
		include_once SPINE_SYS.DS.'Dispatcher.php';
		
		Spine_GlobalRegistry::register('instance', 'request', new Spine_Request());
		Spine_GlobalRegistry::register('instance', 'request_override', new Spine_RequestOverride());
		Spine_GlobalRegistry::register('instance', 'route', new Spine_Route());
		Spine_GlobalRegistry::register('instance', 'dispatcher', new Spine_Dispatcher());
		Spine_GlobalRegistry::register('instance', 'response', new Spine_Response());
	}
	
	//------------------------------------------------------------------------------------
	
	private function handleUserRequest()
	{
		Spine_GlobalRegistry::getRegistryValue('instance', 'request')->requestHandler();
	}
	
	//------------------------------------------------------------------------------------
	
	private function handleGlobalOverride()
	{
		Spine_GlobalRegistry::getRegistryValue('instance', 'request_override')->processGlobalOverride();
	}
	
	//------------------------------------------------------------------------------------
	
	private function loadProjectParameters()
	{
		if (file_exists('parameters.php'))
		{
			include 'parameters.php';
		}
		else
		{
			include 'SPINE/Spine_Core/SpineSys/Default_Parameters.php';
		}
	}
	
	//------------------------------------------------------------------------------------

	private function processUserRequest()
	{
		Spine_GlobalRegistry::getRegistryValue('instance', 'request')->processRequest();
		$this->_userRequest = Spine_GlobalRegistry::getRegistryValue('request', 'uri_path_array');
	}
	
	//------------------------------------------------------------------------------------
	
	private function handleRequestOverride()
	{
		Spine_GlobalRegistry::getRegistryValue('instance', 'request_override')->analyzeRequest();
		$this->_userRequest = Spine_GlobalRegistry::getRegistryValue('request', 'uri_path_array');
	}
	
	//------------------------------------------------------------------------------------
	
	private function execPredefOverride()
	{
		if (Spine_GlobalRegistry::getRegistryValue('override', 'install'))
		{
			include SPINE_CLASSES.DS.'install'.DS.'install.php';
			new install();
		}
		
		if (Spine_GlobalRegistry::getRegistryValue('override', 'showgr'))
			Spine_GlobalRegistry::viewRegistryContent();
	}
	
	//------------------------------------------------------------------------------------
	
	private function setUpRouting()
	{
		$params_start	=	Spine_GlobalRegistry::getRegistryValue('route', 'params_start_at');

		if ($params_start !== FALSE)
		{
			$this->_userRequest	=	array_splice($this->_userRequest, 0, $params_start);	
		}

		Spine_GlobalRegistry::getRegistryValue('instance', 'route')->setUpRoute($this->_userRequest);
		Spine_GlobalRegistry::getRegistryValue('instance', 'route')->setParameters();
	}
	
	//------------------------------------------------------------------------------------
	
	private function dispatch()
	{
		Spine_GlobalRegistry::getRegistryValue('instance', 'dispatcher')->dispatchMainController();
		Spine_GlobalRegistry::getRegistryValue('instance', 'dispatcher')->dispatchRoute();
	}
	
	//------------------------------------------------------------------------------------
	
	private function response()
	{
		Spine_GlobalRegistry::getRegistryValue('instance', 'response')->displayOutput();
	}
	
	//------------------------------------------------------------------------------------
	
	private function startSession()
	{
		include SPINE_CLASSES.DS.'session/SpineSessionClass.php';
		include SPINE_CLASSES.DS.'session/SpineSessionRegistry.php';
		include SPINE_CLASSES.DS.'auth/SpineAuthentication.php';
	}
	
	//------------------------------------------------------------------------------------
}