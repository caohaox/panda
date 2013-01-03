<?php

class LoginController extends Zend_Controller_Action
{

  	private $form;
	
    public function init() {
		$this->form = new Application_Form_Login();
    }
    

    public function logoutAction() {

    	Zend_Auth::getInstance()->setStorage(new Zend_Auth_Storage_Session('site'))->clearIdentity();
    	
    	$this->_redirect('index');
    }
    
    
    public function indexAction() {
    	
    	$this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout()->disableLayout();
        
		$response = $this->getResponse();
		
    	if(!$this->form->isValid($this->getRequest()->getPost())) {

			$response->setBody(json_encode(array('form' => $this->form->render())));
    	}elseif($this->auth()->isValid()) {

    		$response->setBody(json_encode(array('success' => $this->view->baseUrl('/'))));
		}else {

		    $this->form->getElement('password')->addErrors(array($this->translate('Wrong password or email')));
		    $response->setBody(json_encode(array('form' => $this->form->render())));
    	}
    }
    
    private function auth() {

    	$auth = Zend_Auth::getInstance();
    	$auth->setStorage(new Zend_Auth_Storage_Session('site'));
		$authAdapter = new Zend_Auth_Adapter_DbTable(
		    Zend_Registry::get('db'),
		    	'user',
		    	'email',
		    	'password',
		    	'md5(?) AND status = 0 AND block = 0'
		    );
		$authAdapter->setIdentity($this->form->getValue('email'))
		    		->setCredential($this->form->getValue('password'));
                                          			
		$result = $auth->authenticate($authAdapter);    
		if($result->isValid()) {

		    $storage = $auth->getStorage();
		    $resultObject = $authAdapter->getResultRowObject(null, array('password'));
		    $storage->write($resultObject);
		    
		    $this->model('user')->setSessionId($resultObject->user_id, ZEND_SESSION::getId());
		}
		return $result;
    }
}

