<?php 

class install
{
	public function __construct()
	{
		$zip_path	=	dirname(__FILE__);
		$path		=	getcwd();
		copy($zip_path.'/install.zip', $path.DS.'Install.zip');
		$zip = new ZipArchive;
		$zip->open('install.zip');
		$zip->extractTo('./');
		$zip->close();
		
		unlink('install.zip');
		header('location: /');
		exit();
	}
}