<?php

abstract class Spine_OverrideAbstract
{
	public function getOverrideArray()
	{
		return $this->overrides_array;
	}
	
	public function ec()
	{
		echo "<span styles='font-color:red;'> ECHO </span>".
			"<div> I have been crucified with ".
			"<br/><b><h1>Christ</h1></b> never the less".
			"<br/> I live, yet not I but <b><h1>Christ</h1></b>".
			"<br/> who lives in me and the life which now I live".
			"<br/> I live by faith in the <b>Son of God</b>".
			"<br/> who loved me and gave <b>Himself</b> for me.".
			"<br/><br/>-Galatians 2:20 ";
		
		die();
	}
	
	public function showGR()
	{
		Spine_GlobalRegistry::register('override', 'showgr', TRUE);
	}
	
	public function install()
	{
		Spine_GlobalRegistry::register('override', 'install', TRUE);
	}
}