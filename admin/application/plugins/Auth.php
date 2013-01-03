<?php 
class Application_Plugin_Auth extends Zend_Controller_Plugin_Abstract {
	
	public function preDispatch(Zend_Controller_Request_Abstract $request) {
		$view = Zend_Registry::get('view');
		
		if(Zend_Auth::getInstance()->setStorage(new Zend_Auth_Storage_Session('admin_area'))->hasIdentity()) {
			$view->admin = Zend_Auth::getInstance()->setStorage(new Zend_Auth_Storage_Session('admin_area'))->getIdentity();
		}else {
			$view->admin = false;
			$request->setControllerName('login');
			$request->setActionName('index');
		}
	}
}