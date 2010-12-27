<?php

namespace App;

class Application
{
		
	public function __construct()
	{
		$mustache = new \App\Mustache\Mustache();
		$facebook = new \Facebook(array(
		  'appId'  => FACEBOOK_APP_ID,
		  'secret' => FACEBOOK_APP_SECRET
		));
		
		\Zend_Registry::set('mustache', $mustache);
		\Zend_Registry::set('facebook', $facebook);
		
	}
	
	public function run()
	{
		try {
			var_dump($_SERVER);
			$action = $this->getActionClass($_SERVER['REQUEST_URI']);
			$action->run();
			
		} catch (\Exception $e) {
			$action = new Action\ErrorAction();
			$action->setError($e);
			$action->run();
		}
	}
	
	private function getActionClass($name)
	{
		//Strip rest of url if not in its own VirtualHost
		$name = substr($name, strrpos($name, 'public') );
		$name = str_replace('public/', '', $name);
		var_dump($name);
		$name = ($name == "" || null === $name || $name == "/")? 'list':$name;
		var_dump($name);
		if (strpos($name, '/') !== false) {
			$name = strstr($name, '/', true);
		}
		var_dump($name);
		$name = str_replace('-', ' ', $name);
		var_dump($name);
		$name = ucwords($name);
		var_dump($name);
		$name = str_replace(' ', '', $name);
		var_dump($name);
		
		$class = 'App\\Action\\' . $name . 'Action';
		var_dump($class);var_dump(class_exists($class));
		if (!class_exists($class)){
			$action = new \App\Action\ErrorAction();
			$action->setError( new \Exception('Action '.$name.' not defined') );

			return $action;
		}
		
		return new $class;
		
	}
}

?>