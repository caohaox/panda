<?php

class LoginController extends Zend_Controller_Action
{
	
	private $form;
	
    public function init()
    {
    	$this->form = new Application_Form_Login();
    }

    public function indexAction()
    {
        if($this->getRequest()->isPost()) {
	    	if($this->form->isValid($this->getRequest()->getPost())) {
	    		if($this->auth()->isValid()) {
	    			$this->_redirect('/');
	    		}else {
	    			$this->form->getElement('login')->addError($this->translate('Wrong password or login'));
	    			$this->view->loginForm = $this->form;
	    		}
	    	}else $this->view->loginForm = $this->form;
        }else {
        	$this->view->loginForm = $this->form;
        }
    }
    
    public function logoutAction() {
    	$this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout()->disableLayout();
        
    	Zend_Auth::getInstance()->setStorage(new Zend_Auth_Storage_Session('admin_area'))->clearIdentity();
    	$this->_redirect('login/index');
    }
    
    private function auth() {
    	$auth = Zend_Auth::getInstance();
    	$auth->setStorage(new Zend_Auth_Storage_Session('admin_area'));
		$authAdapter = new Zend_Auth_Adapter_DbTable(
		    Zend_Registry::get('db'),
		    	'admin',
		    	'login',
		    	'password',
		    	'md5(?)'
		    );
		$authAdapter->setIdentity($this->form->getValue('login'))
		    		->setCredential($this->form->getValue('password'));
		    				
		$result = $auth->authenticate($authAdapter);
		if($result->isValid()) {
		    $storage = $auth->getStorage();
		    $storage->write($authAdapter->getResultRowObject(null, array('password')));
		}
		return $result;
    }
}

