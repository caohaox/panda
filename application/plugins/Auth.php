<?php 
class Application_Plugin_Auth extends Zend_Controller_Plugin_Abstract {
	
	public function preDispatch(Zend_Controller_Request_Abstract $request) {
		$view = Zend_Registry::get('view');
		
		
		if(Zend_Auth::getInstance()->setStorage(new Zend_Auth_Storage_Session('site'))->hasIdentity()) {
			//var_dump(Zend_Auth::getInstance()->setStorage(new Zend_Auth_Storage_Session('site'))->getIdentity());
			$view->user = Zend_Auth::getInstance()->setStorage(new Zend_Auth_Storage_Session('site'))->getIdentity();
		}else {
			$view->user = false;
		}
		
	}
}