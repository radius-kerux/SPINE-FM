<?php

class Spine_Response extends Spine_ViewRenderer
{
	public function displayOutput()
	{
		$this->renderTemplateStack();
		$this->renderStylesheetStack();
		$this->renderExternalStylesheetsStack();
		$this->renderGlobalScriptStack();
		$this->renderExternalScriptStack();
		$this->renderLocalScriptStack();
		$this->sendHeaders();
		$this->printFinalTemplate();
	}
	
	//------------------------------------------------------------------------------------
	
	private function printFinalTemplate()
	{
		//header($string);
		ob_start();
		
		echo Spine_GlobalRegistry::getRegistryValue('response', 'final_template');
		
		if (Spine_GlobalRegistry::getRegistryValue('response', 'cache_final_template'))
		{
			if (!file_exists(SITE.'/data/cache/templates/'))
				mkdir(SITE.'/data/cache/templates/', 0777);
				
			$id			=	Spine_GlobalRegistry::getRegistryValue('response', 'cache_id');
			$controller	=	Spine_GlobalRegistry::getRegistryValue('route', 'controller');
			$method		=	Spine_GlobalRegistry::getRegistryValue('route', 'method');
			$filename	=	SITE.'/data/cache/templates/'.sha1($controller.$method.$id).'.phtml';
			
			$template	=	ob_get_contents();
			file_put_contents($filename, $template);
		}
		
		ob_end_flush();
	}
	
	//------------------------------------------------------------------------------------
	
	private function sendHeaders()
	{
		$headers	=	Spine_GlobalRegistry::getRegistryValue('response', 'page_headers');
		foreach ($headers as $header)
		{
			header($header);
		}
	
	}
}