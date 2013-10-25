<?php

class counselorController extends Spine_SuperController
{
	public function main()
	{
		$this->loadModel('users/user');
		$this->loadModel('users/users');
	}
	
	public function indexAction()
	{
		$year = date('Y');
		$month = date('m');
	
		$user	=	new user();
		
		$event	=	json_encode(array(
	
		array(
			'id' => 111,
			'title' => "Event1",
			'start' => "$year-$month-10",
			'url' => "sdf"
		),
		
		array(
			'id' => 222,
			'title' => "Event2",
			'start' => "$year-$month-20",
			'end' => "$year-$month-22",
			'url' => "sd"
		)
	
		));
		
		$user_data	=	$user->select( array( 'user_id' => $_GET['id'] ) );
		$user_data	=	$user_data[0];
		$user_data['events']	=	$event;
		
		$this->renderTemplate('dashboard', 'panel/counselor/counselor_dashboard', $user_data);
		$this->renderTemplate('calendar', 'panel/calendar');
	}
	
	public function eventsAction ()
	{
		$year = date('Y');
		$month = date('m');
		
		$event	=	json_encode(array(
	
		array(
			'id' => 111,
			'title' => "Event1",
			'start' => "$year-$month-10",
			'url' => "http://yahoo.com/"
		),
		
		array(
			'id' => 222,
			'title' => "Event2",
			'start' => "$year-$month-20",
			'end' => "$year-$month-22",
			'url' => "http://yahoo.com/"
		)
	
		));
		
		echo $event;die;
	}
	
	public function logoutAction()
	{
	}
	
	public function userControlAction ()
	{
	}
	
	private function getUserIfo ()
	{
		
	}
}