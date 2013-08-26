<?php
/**
 * 
 * Routing configuration file using array for better performance
 * it follows certain conventions like always having _name for the controller name
 * inside the route name index
 * and having _default in the route array to set the default controller
 * @var unknown_type
 */

$routes = array (
	'home'	=>	array( // this is the route name index
			'_name'	=> 'home', //indexes that start with underscore are keywords
					'learn-more'	=>	array(	//from here on the indexes will be treated as folder name
						'_name'	=> 'learn'
			)
		),
	'about-us'	=>	array(
			'_name'	=>	'about'
		),
	'contact-us'	=>	array(
			'_name'	=>	'contact'
		),
		
	'_default'	=>	'home' //so home is the dafault
);

//uncomment this if you do not want to use routes
//$routes	=	FALSE;