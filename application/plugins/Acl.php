<?php
class Application_Plugin_Acl extends Zend_Controller_Plugin_Abstract {
	
	
	public function preDispatch(Zend_Controller_Request_Abstract $request) {
		
		$auth = Zend_Auth::getInstance();
		$auth->setStorage(new Zend_Auth_Storage_Session('site'));
		
		$acl = new Zend_Acl();
		
		$acl->addRole(new Zend_Acl_Role('guest'));
		$acl->addRole(new Zend_Acl_Role('user')); //пользователь со всеми заполнеными шагами
		$acl->addRole(new Zend_Acl_Role('user_step')); //пользователь с незаполнеными шагами
		
		//controllers
		$acl->add(new Zend_Acl_Resource('index'));
		$acl->add(new Zend_Acl_Resource('page'));
		$acl->add(new Zend_Acl_Resource('register'));
		$acl->add(new Zend_Acl_Resource('error'));
		$acl->add(new Zend_Acl_Resource('errors'));
		$acl->add(new Zend_Acl_Resource('login'));
		$acl->add(new Zend_Acl_Resource('gallery'));
		$acl->add(new Zend_Acl_Resource('profile'));
		$acl->add(new Zend_Acl_Resource('user'));
		$acl->add(new Zend_Acl_Resource('steps'));
		$acl->add(new Zend_Acl_Resource('filemanager'));
		$acl->add(new Zend_Acl_Resource('chat'));
		$acl->add(new Zend_Acl_Resource('friends'));
		$acl->add(new Zend_Acl_Resource('recomendations'));
		$acl->add(new Zend_Acl_Resource('requests'));
		$acl->add(new Zend_Acl_Resource('account'));
		
		$acl->deny();
		
		//guest
		$acl->allow('guest', 'index', null);
		$acl->allow('guest', 'page', null);
		$acl->allow('guest', 'register', null);
		$acl->allow('guest', 'error', null);
		$acl->allow('guest', 'errors', null);
		$acl->allow('guest', 'login', null);
		
		//user_step
		$acl->allow('user_step', 'page', null);
		$acl->allow('user_step', 'index', 'language');
		$acl->allow('user_step', 'login', 'logout');
		$acl->allow('user_step', 'error', null);
		$acl->allow('user_step', 'errors', null);
		$acl->allow('user_step', 'steps', null);
		
		//user
		$acl->allow('user', null, null);
		$acl->deny('user', 'register', null);
		$acl->deny('user', 'login', null);
		$acl->allow('user', 'login', 'logout');
		$acl->deny('user', 'index', null);
		$acl->allow('user', 'index', 'language');

		
		if($auth->hasIdentity()) {
			$user = $auth->getIdentity();
			if(!$user->step1 || !$user->step2 || !$user->step3) {
				$role = 'user_step';
			}else $role = 'user';
		}else {
			$role = 'guest';
		}
		
		//$controller = $request->controller;
		$controller = $request->getControllerName();
		$action = $request->getActionName();
		
		//if access is denied, person is redirected to the error page
		//$acl->has($controller)
		if(!$acl->isAllowed($role, $controller, $action)) {
			//пропускаем главную страницу для зарегистрирвоаных пользователей
			if(($role == 'user') && ($controller == 'index')) {
				$request->setControllerName('gallery');
				$request->setActionName('index');
			}elseif($role == 'user_step') {
				//перенаправляем на шаги ввода данных если они не заполнены
				if(!$user->step1) {
					$request->setControllerName('steps');
					$request->setActionName('step1');
				}elseif(!$user->step2) {
					$request->setControllerName('steps');
					$request->setActionName('step2');
				}elseif(!$user->step3) {
					$request->setControllerName('steps');
					$request->setActionName('step3');
				}
			}else {
				$request->setControllerName('errors');
				$request->setActionName('access');
			}
		}
		
		Zend_Registry::set('acl', $acl);
		//use in view:
		//if($this->user) {
		//	if(Zend_Registry::get('acl')->isAllowed($role, $resource, $privilege)) {
	}
}