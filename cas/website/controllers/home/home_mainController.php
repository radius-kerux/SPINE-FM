<?php

/**
 * 
 * this _main controller set the theme for every other controller within this folder
 * the name of this main controller should be foldername_mainController
 * @author momon
 *
 */
class home_mainController extends Spine_SuperController implements Spine_MainInterface
{
	
	public function main()
	{
		$params = array(
							'description'	=>	'Spine Website'
		);
		$this->renderTemplate('main_content', 'home/main', $params);
	}
	
	public function end()
	{
	}
	
}