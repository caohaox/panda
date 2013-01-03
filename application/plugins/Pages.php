<?php
class Application_Plugin_Pages extends Zend_Controller_Plugin_Abstract {
	
	
	public function preDispatch(Zend_Controller_Request_Abstract $request) {
		
		$front = Zend_Controller_Front::getInstance();
		$dispatcher = $front->getDispatcher();
		$controllerName = $request->getControllerName();
		$class      = $dispatcher->getControllerClass($request);
 		$controllerDirectory = $front->getControllerDirectory();
		
 		//check if there exists controllers, if not add page route
		if(!is_file($controllerDirectory['default'] . '/' . $class . '.php')) {
			$request->setControllerName('page');
			$request->setActionName('index');
			$request->setParam('pageName', $controllerName);
		}
		
	}
}