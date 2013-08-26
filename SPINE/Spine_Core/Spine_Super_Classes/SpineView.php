<?php
class Spine_SuperView extends Spine_Master
{
	private $template_parameter;
	public static $spine_url_parameters;
	
	/*
	 * calls a block within the application
	 * 
	 * param $block_class - path of the block class
	 * param $method - method to be called within the specified class
	 * uses show as its default value
	 */

	public function displayBlock($block_class, $method = 'show')
	{
		if (!empty($block_class))
			if (file_exists(SITE.DS.'blocks'.DS.$block_class.'Block.php'))
			{
				include_once SITE.DS.'blocks'.DS.$block_class.'Block.php';
				
				if ($pos = strripos($block_class, '/'))
				{
					$block_class	=	trim(substr($block_class, $pos), '/');
				}
				
				$block_class	=	$block_class.'Block';
				$block_class	=	new $block_class();
				
				echo $block_class->$method();
			}
			else
				die('Cannot find file '.SITE.DS.'blocks'.DS.$block_class.'.php');
		else
			die('Empty Class? Seriously');
	}
	
	public function displayTemplate($template = '')
	{
		$this->template_parameter	=	Spine_GlobalRegistry::getRegistryValue('response', 'spine::template_parameters');
		
		if (!is_null($this->template_parameter))
			extract($this->template_parameter);
			
		if (!empty($template))
		{
			$template = SITE.'/views/'.$template.'.template.phtml';
			
			if (file_exists($template))
			{
				ob_start();
				include_once $template;
				$template = ob_get_contents();
				ob_end_clean();
				
				print $template;
			}
		}
		else
			return FALSE;
	}
	
	public function isActive($controller_name)
	{
		if ($this->getRouteController() == $controller_name.'Controller')
			echo "active";
	}
}