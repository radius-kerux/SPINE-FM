<?php
class mainController extends Spine_SuperController implements Spine_MainInterface
{
	public function main()
	{
		$this->loadClasses();
		
		$params = array(
							'title'=>'Radius Web',
		);
		
		$this->renderTemplate('main_phtml', 'main/main', $params); //Rendering of main template
		$this->renderTemplate('header', 'main/header');
		$this->getGlobalScripts();
		$this->getLocalScripts();
		$this->getProjectStylesheets();
		//Spine_GlobalRegistry::viewRegistryContent();
	}
	
	public function end()
	{
		$this->renderTemplate('footer', 'main/footer');
	}
	
	public function getGlobalScripts()
	{
		$this->renderGlobalScript('jqv-1.9.1', 'plugins/jquery/jquery-1.9.1.min.js');
		$this->renderGlobalScript('jquery.php', 'plugins/php.jquery/javascript/jquery.php.js');
		$this->renderGlobalScript('bootstrap', 'plugins/bootstrap/js/bootstrap.min.js');
	}
	
	public function getProjectStylesheets()
	{
		$this->renderStyleSheet('bootstrap', 'plugins/bootstrap/css/bootstrap.min.css');
		$this->renderStyleSheet('header', SITE.'/views/main/css/header.css');
		$this->renderStyleSheet('footer', SITE.'/views/main/css/footer.css');
		$this->renderStyleSheet('main', SITE.'/views/main/css/main.css');
	}
	
	public function getLocalScripts()
	{
		$this->renderLocalScript('local_top_script','main/js/local_top_script.js');
		$this->renderLocalScript('local_bottom_script','main/js/local_bottom_script.js');
	}
	
	private function loadClasses ()
	{
		$classes_array	=	array(
			'pdo_helper/Db.class',
			'auth/Authentication'
		);
		
		$this->loadSpineClasses($classes_array);
	}
}