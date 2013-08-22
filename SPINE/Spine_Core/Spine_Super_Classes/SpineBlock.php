<?php

/**
 * The concept of blocks defines a pretty automous unit of the application
 * they have their own models and views
 * they must be loosely coupled
 * 
 *  @todo give url access to blocks
 */

class Spine_SuperBlock extends Spine_Master
{
	protected function renderTemplate($template, array $params = array())
	{
		if (!empty($params))
		{
			extract($params);
		}
		
		$template = SITE.DS.'blocks'.DS.$template.'.template.phtml';

		if (file_exists($template))
		{
			ob_start();
			include_once $template;
			$template = ob_get_contents();
			ob_end_clean();
		}
		else
			$template	=	'File called by block doesn\'t exist';
		
		return $template;
	}
}