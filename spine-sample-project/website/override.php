<?php

class override extends Spine_OverrideAbstract
{
	protected $overrides_array	=	array(
		'sitemap.xml'	=>	array(
			'call_back'	=>	'displayXml'
		),
		
		'_info'			=>	array(
			'call_back'	=>	'siteInfo',
			'parameters'=>	array(
				'name'		=>	'Your Name',
				'message'	=>	'Your message'
			),
			'exit'		=>	TRUE	
		)
	);

	public function showPHPInfo()
	{
		echo 'Access Forbidden';
	}
	
	public function siteInfo($parameters = array())
	{
		echo 'Hi '.$parameters['name'].'!'.
			'<br/>'.$parameters['message'];
	}
}