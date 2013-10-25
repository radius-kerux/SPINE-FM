<?php
class homeController extends Spine_SuperController
{
	public function main()
	{
		$this->renderTemplate('inner_most', 'home/set');
	}
	
	public function indexAction()
	{
	}
}