<?php
/**
 * 
 * Renders phtml files so php can send them to client's browser
 * @author Raymond Baldonado
 *
 */
class Spine_ViewRenderer  extends Spine_SuperView
{
	/**
	 * 
	 * Process the stackfull of phtml referrences for browser display
	 */
	
	public function renderTemplateStack()
	{
		//get the stack stored in global registry under templates designation
		$stack = Spine_GlobalRegistry::getDesignationArray('templates');
		$parameters_array = Spine_GlobalRegistry::getRegistryValue('response', 'spine::template_parameters');

		if ($parameters_array != false) //passed template parameters
		{
			extract($parameters_array);
		}
		//gets the passed parameters via url in the registry 	
		if (count (Spine_GlobalRegistry::getRegistryValue('request', 'parameters_array')) != 0)
			self::$spine_url_parameters = Spine_GlobalRegistry::getRegistryValue('request', 'parameters_array');
			
		$final_template = '';
		
		foreach($stack as $key => $template)
		{
			if (strpos($key, 'REMOVE')) //using REMOVE keyword to ommit a defined section
			{
				$final_template = str_replace('<spine::'.$key.'/>', '', $final_template);
			}
			elseif (strpos($key, 'main_phtml') !== false) //main_phtml is the top template, render the top template first
			{
				if (file_exists($template))
				{
					ob_start(); //object buffer to get the contents
					include_once $template;
					$final_template = ob_get_contents();
					ob_end_clean();
				}
				else 
					die('main_phtml is missing.');
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
	
	//------------------------------------------------------------------------------------
	
	/**
	 * 
	 * Renders stackfull of css to a single css file 
	 * that is located at data/cache/spine.cache.css
	 */
	
	public function renderStylesheetStack()
	{
		$final_template	=	Spine_GlobalRegistry::getRegistryValue('response', 'final_template');
		$stack			=	Spine_GlobalRegistry::getDesignationArray('stylesheets');

		$final_stylesheets = '';
		$final_stylesheets_key = 'global_stylesheet';
		$filename = 'spine';
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
					die('what is happening with css? '.$stylesheet);
			}
		
		if (!empty($final_stylesheets))
		{
			file_put_contents(SITE.'/data/cache/spine.cache.css', $final_stylesheets);
			$final_template = str_replace('<spine::'.$final_stylesheets_key.'/>', '<link rel="stylesheet" type="text/css" href="/'.SITE.'/data/cache/'.$filename.'.cache.css">', $final_template);
			Spine_GlobalRegistry::register('response', 'final_template', $final_template);
		}
		
	}
	
	//------------------------------------------------------------------------------------
	
	public function renderExternalStylesheetsStack()
	{
		$final_template	=	Spine_GlobalRegistry::getRegistryValue('response', 'final_template');
		$indexes		=	Spine_GlobalRegistry::getDesignationArray('external_stylesheets');
		
		if ($indexes !== false)
			foreach($indexes as $index_key => $index)
			{
				$final_stylesheets = '';
				foreach ($index as $stack_key => $stylesheet)
				{
					if (file_exists($stylesheet))
					{
						ob_start();
						include_once $stylesheet;
						$final_stylesheets .= ob_get_contents();				
						ob_end_clean();
					}
					else
						die('what is happening with stylesheet? '.$stylesheet);

					file_put_contents(SITE.'/data/cache/'.$index_key.'.cache.css', $final_stylesheets);
					$final_template = str_replace('<spine::'.$index_key.'/>', '<link rel="stylesheet" type="text/css" href="/'.SITE.'/data/cache/'.$index_key.'.cache.css">', $final_template);
					Spine_GlobalRegistry::register('response', 'final_template', $final_template);
				}
			}
	}
	
	//------------------------------------------------------------------------------------
	
	/**
	 * 
	 * Renders the stored scripts in the stack to a single file
	 * that is located at data/cache/spine.cache.js
	 */
	
	public function renderGlobalScriptStack($final_scripts_key = 'global_script', $filename = 'spine')
	{
		$final_template	=	Spine_GlobalRegistry::getRegistryValue('response', 'final_template');
		$stack			=	Spine_GlobalRegistry::getDesignationArray('global_scripts');

		$final_scripts = '';
		//$final_scripts_key = 'global_script'; //find way to put this in a configuration file
		
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
		if (!empty($final_scripts))
		{
			file_put_contents(SITE.'/data/cache/spine.cache.js', $final_scripts);
			$final_template = str_replace('<spine::'.$final_scripts_key.'/>', '<script src="/'.SITE.'/data/cache/'.$filename.'.cache.js"></script>', $final_template);
			Spine_GlobalRegistry::register('response', 'final_template', $final_template);
		}
		
	}
	
	//------------------------------------------------------------------------------------
	
	/**
	 * 
	 * Renders the stored scripts in the stack to a single file
	 * that is located at data/cache/spine.cache.js
	 */
	
	public function renderExternalScriptStack()
	{
		$final_template	=	Spine_GlobalRegistry::getRegistryValue('response', 'final_template');
		$indexes		=	Spine_GlobalRegistry::getDesignationArray('external_scripts');
		
		if ($indexes !== false)
			foreach($indexes as $index_key => $index)
			{
				$final_scripts = '';
				foreach ($index as $stack_key => $script)
				{
					if (file_exists($script))
					{
						ob_start();
						include_once $script;
						$final_scripts .= ob_get_contents();				
						ob_end_clean();
					}
					else
						die('what is happening with script? '.$script);
				}
				
				file_put_contents(SITE.'/data/cache/'.$index_key.'.cache.js', $final_scripts);
				$final_template = str_replace('<spine::'.$index_key.'/>', '<script src="/'.SITE.'/data/cache/'.$index_key.'.cache.js"></script>', $final_template);
				Spine_GlobalRegistry::register('response', 'final_template', $final_template);
			}
	}
	
	//------------------------------------------------------------------------------------
	
	/**
	 * 
	 * renders local script stored in the stack
	 */
	
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
	
	//------------------------------------------------------------------------------------
	
	/**
	 * 
	 * extract the parameters passed in the templates so the indexes of parameters in array format
	 * can be used as a variable
	 */
	
	private function extractParams()
	{
		$parameters_array = Spine_GlobalRegistry::getRegistryValue('response', 'spine::template_parameters');

		if ($parameters_array != false) //passed template parameters
		{
			extract($parameters_array);
		}
	}
	
}