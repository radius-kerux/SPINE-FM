<?php
class helloworldBlock extends Spine_SuperBlock
{
	public function sayHello()
	{
		$params	=	array(
			'name'	=> 'Raymond'
		);
		
		return $this->renderTemplate('sample/views/main', $params);
	}
	
	public function test()
	{
		echo 'good one!';
	}
}