<?php

class Spine_ViewRenderer  extends Spine_SuperView
{
	
	public function renderTemplateStack()
	{
		$stack = Spine_GlobalRegistry::getDesignationArray('templates');
		$parameters_array = Spine_GlobalRegistry::getRegistryValue('response', 'spine::template_parameters');

		if ($parameters_array != false) //passed template parameters
		{
			extract($parameters_array);
		}
			
		if (count (Spine_GlobalRegistry::getRegistryValue('request', 'parameters_array')) != 0)
			self::$spine_url_parameters = Spine_GlobalRegistry::getRegistryValue('request', 'parameters_array');
			
		$final_template = '';
		
		foreach($stack as $key => $template)
		{
			if (strpos($key, 'REMOVE'))
			{
				$final_template = str_replace('<spine::'.$key.'/>', '', $final_template);
			}
			elseif (strpos($key, 'main_phtml') !== false) //main_phtml is the top template
			{
				if (file_exists($template))
				{
					ob_start();
					include_once $template;
					$final_template = ob_get_contents();
					ob_end_clean();
				}
				else 
					die('Where is your main template?');
			}
			elseif (file_exists($template))
			{
				ob_start();
				include_once $template;
				$final_template = str_replace('<spine::'.$key.'/>', ob_get_contents(), $final_template);				
				ob_end_clean();
			}
			else
				 die('what is happening?');
		}
		
		Spine_GlobalRegistry::register('response', 'final_template', $final_template);
	}
	
	public function renderStylesheetStack()
	{
		$final_template = Spine_GlobalRegistry::getRegistryValue('response', 'final_template');
		$stack = Spine_GlobalRegistry::getDesignationArray('stylesheets');

		$final_stylesheets = '';
		$final_stylesheets_key = 'global_stylesheet';
		
		if ($stack !== false)
			foreach($stack as $key => $stylesheet)
			{
				if (file_exists($stylesheet))
				{
					ob_start();
					include_once $stylesheet;
					$final_stylesheets .= ob_get_contents();				
					ob_end_clean();
				}
				else
					die('what is happening with css?');
			}
		
		file_put_contents(SITE.'/data/cache/spine.cache.css', $final_stylesheets);
		$final_template = str_replace('<spine::'.$final_stylesheets_key.'/>', '<link rel="stylesheet" type="text/css" href="/'.SITE.'/data/cache/spine.cache.css">', $final_template);
		Spine_GlobalRegistry::register('response', 'final_template', $final_template);
	}
	
	public function renderGlobalScriptStack()
	{
		$final_template = Spine_GlobalRegistry::getRegistryValue('response', 'final_template');
		$stack = Spine_GlobalRegistry::getDesignationArray('global_scripts');

		$final_scripts = '';
		$final_scripts_key = 'global_script'; //find way to put this in a configuration file
		
		if ($stack !== false)
			foreach($stack as $key => $script)
			{
				if (file_exists($script))
				{
					ob_start();
					include_once $script;
					$final_scripts .= ob_get_contents();				
					ob_end_clean();
				}
				else
					die('what is happening with script?');
			}

		file_put_contents(SITE.'/data/cache/spine.cache.js', $final_scripts);
		$final_template = str_replace('<spine::'.$final_scripts_key.'/>', '<script src="/'.SITE.'/data/cache/spine.cache.js"></script>', $final_template);
		Spine_GlobalRegistry::register('response', 'final_template', $final_template);
	}
	
	public function renderLocalScriptStack()
	{
		
		$parameters_array = Spine_GlobalRegistry::getRegistryValue('response', 'spine::template_parameters');

		if ($parameters_array != false) //passed template parameters
		{
			extract($parameters_array);
		}
		
		$final_template = Spine_GlobalRegistry::getRegistryValue('response', 'final_template');
		$stack = Spine_GlobalRegistry::getDesignationArray('local_scripts');
		$final_scripts = '';
		
		$final_scripts_key = 'local_bottom_script'; //find way to put this in a configuration file

		if ($stack !== false)
			foreach($stack as $key => $position)
			{
				foreach ($position as $script)
				{
					if (file_exists($script))
					{
						ob_start();
						include_once $script;
						$final_scripts .= ob_get_contents();				
						ob_end_clean();
					}
					else
						die($script);
				}
					
				$final_scripts_key = $key;
				$final_template = str_replace('<spine::'.$final_scripts_key.'/>',$final_scripts, $final_template);
			}
		Spine_GlobalRegistry::register('response', 'final_template', $final_template);
	}
	
	private function extractParams()
	{
		$parameters_array = Spine_GlobalRegistry::getRegistryValue('response', 'spine::template_parameters');

		if ($parameters_array != false) //passed template parameters
		{
			extract($parameters_array);
		}
	}
	
}