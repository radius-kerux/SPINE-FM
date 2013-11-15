<?php
/**
 * 
 * Abstract of routes file in the projects
 * just so we can be sure that getRoutes is found somewhere in the projects code
 * @author Raymond Baldonado
 *
 */

//------------------------------------------------------------------------------------
	
	abstract class Spine_RouteAbstract
	{
		abstract public function getRoutes();
	}