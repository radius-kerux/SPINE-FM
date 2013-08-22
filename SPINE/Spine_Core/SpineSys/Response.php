<?php

class Spine_Response extends Spine_ViewRenderer
{
	public function displayOutput()
	{
		$this->renderTemplateStack();
		$this->renderStylesheetStack();
		$this->renderGlobalScriptStack();
		$this->renderLocalScriptStack();
		print Spine_GlobalRegistry::getRegistryValue('response', 'final_template');
	}
}