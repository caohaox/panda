<?php

class RegisterController extends Zend_Controller_Action {

	private $form;
	

	public function successAction() {
		
		$this->view->text = $this->translate('RegisterStep2Text');
		$this->view->title = $this->translate('RegisterStep2Title');
		
		if($this->_getParam('user_id')) {
			$this->view->button = $this->translate('Resend letter');
			$this->view->button_href = $this->view->baseUrl('register/resend/user_id/' . $this->_getParam('user_id'));
		}
	}
	
	
	public function resendAction() {
		$this->_helper->viewRenderer->setRender('common/success', null , true);
		if($this->_getParam('user_id')) {
			$this->model('user')->resendRegisterMail($this->_getParam('user_id'));
		}
		
		$this->view->text = $this->translate('RegisterStep2Text');
		$this->view->title = $this->translate('RegisterStep2Title');
	}
	
	
	public function getgentlemenformAction() {
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout()->disableLayout();
		$this->form = new Application_Form_Register_RegisterGentlemen();
		$response = $this->getResponse();
		$response->setBody($this->form);
	}
	
	public function getwomenformAction() {
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout()->disableLayout();
		$this->form = new Application_Form_Register_RegisterWomen();
		$response = $this->getResponse();
		$response->setBody($this->form);
	}


	public function registergentlemenAction() {
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout()->disableLayout();

		if($this->getRequest()->isXmlHttpRequest()) {
			$response = $this->getResponse();
			$this->form = new Application_Form_Register_RegisterGentlemen();
			if(!$this->form->isValid($this->getRequest()->getPost())) {
				$response->setBody(json_encode(array('form' => $this->form->render(), 'success' => false)));
			}else {
				$user_id = $this->model('user')->addGentleman($this->form->getValues());
				$response->setBody(json_encode(array('success' => $this->view->baseUrl('/register/success/user_id/' . $user_id))));
			}
		}
	}
	
	public function registerwomenAction() {
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout()->disableLayout();

		if($this->getRequest()->isXmlHttpRequest()) {
			$response = $this->getResponse();
			$this->form = new Application_Form_Register_RegisterWomen();
			if(!$this->form->isValid($this->getRequest()->getPost())) {
				$response->setBody(json_encode(array('form' => $this->form->render(), 'success' => false)));
			}else {
				$user_id = $this->model('user')->addWoman($this->form->getValues());
				$response->setBody(json_encode(array('success' => $this->view->baseUrl('/register/success/user_id/' . $user_id))));
			}
		}
	}


	public function confirmAction() {
		$this->_helper->viewRenderer->setRender('common/success', null , true);
		$this->view->title = $this->translate('RegisterConfirmTitle');
		if($this->_getParam('code')) {
			$user_id = $this->model('user')->confirm($this->_getParam('code'));
			if($user_id) {
				$this->auth($user_id);
				$this->_redirect('steps/step1');
				//$this->view->text = $this->translate('RegisterConfirmText');
			}else $this->view->text = $this->translate('RegisterConfirmError');
		}else $this->view->text = $this->translate('RegisterConfirmError');
	}
	
	
	//аутефикация без пароля
	private function auth($user_id) {
		$user = $this->model('user')->getUser($user_id);
		$auth = Zend_Auth::getInstance();
    	$auth->setStorage(new Zend_Auth_Storage_Session('site'));

		$storage = $auth->getStorage();
		$storage->write((object)$user);
	}
	
	
	//asks email and send code to email
	public function forgetAction() {
		$form = new Application_Form_Register_Forget();
		$this->view->title = $this->translate('Password restore');
		if($this->getRequest()->isPost()) {
			if($form->isValid($this->getRequest()->getPost())) {
				$db = new Application_Model_User();
				$result = $db->forgetPasswordStep1($form->getValues());
				if($result) {
					
					$sendMail = new Zend_Mail('UTF-8');
					$sendMail->setSubject($this->translate('Password restore'));
					$sendMail->addTo($form->getValue('email'));
					$sendMail->setFrom(Site_Config::get('admin_email'), Site_Config::get('site_name'));
					$sendMail->setBodyHtml(html_entity_decode(sprintf($this->translate('PasswordRestoreText'), Site_Config::get('site_name'), Site_Config::get('site_url') . 'register/restore/code/' . $result), ENT_QUOTES, 'UTF-8'));
					$sendMail->send();
					
					$this->_helper->viewRenderer->setRender('common/success', null , true);
					$this->view->text = $this->translate('EmailSentText');
				}else {
					$this->_helper->viewRenderer->setRender('common/success', null , true);
					$this->view->text = $this->translate('EmailSentError');
				}
			}else {
				$this->view->form = $form;
			}
		}else $this->view->form = $form;
	}
	
	
	//check code and change password
	public function restoreAction() {
		
		if($this->_getParam('code')) {
			$this->_helper->viewRenderer->setRender('forget');
			$this->view->title = $this->translate('Password restore');
			$db = new Application_Model_User();
			$result = $db->checkRestoreCode($this->_getParam('code'));
			if(!$result['user_id']) {
				$this->_helper->viewRenderer->setRender('common/success', null , true);
				$this->view->text = $this->translate('PasswordRestoreError');
			}else {
				$form = new Application_Form_Register_Restore();
				$form->setAction($this->view->baseUrl('register/restore/code/' . $this->_getParam('code')));
				if($this->getRequest()->isPost()) {
					if($form->isValid($this->getRequest()->getPost())) {
						$db->forgetPasswordStep2($form->getValues(), $result['user_id']);
						
						//$sendMail = new Zend_Mail('UTF-8');
						//$sendMail->setSubject($this->translate('Your password was changed'));
						//$sendMail->addTo($result['email']);
						//$sendMail->setFrom(Site_Config::get('admin_email'), Site_Config::get('site_name'));
						//$sendMail->setBodyHtml(html_entity_decode($this->translate('Your password was changed'), ENT_QUOTES, 'UTF-8'));
						//$sendMail->send();
						
						$this->_helper->viewRenderer->setRender('common/success', null , true);
						$this->view->text = $this->translate('Your password was changed');
					}else {
						$this->view->title = $this->translate('Enter your new password');
						$this->view->form = $form;
					}
				}else {
					$this->view->title = $this->translate('Enter your new password');
					$this->view->form = $form;
				}
			}
		}else {
			$this->_helper->viewRenderer->setRender('common/success', null , true);
			$this->view->text = $this->translate('PasswordRestoreError');
		}
	}
	
	
	//ajax
	public function reloadmenAction() {
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout()->disableLayout();
		
		$this->form = new Application_Form_Register_RegisterGentlemen();
		$captcha = $this->form->getElement('captcha')->getCaptcha();

		$response = array();

		$response['id']  = $captcha->generate();
		$response['img'] = $captcha->getImgUrl() . $captcha->getId() . $captcha->getSuffix();
		
		$this->getResponse()->setBody(json_encode($response));
	}
	
	//ajax
	public function reloadwomenAction() {
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout()->disableLayout();
		
		$this->form = new Application_Form_Register_RegisterWomen();
		$captcha = $this->form->getElement('captcha')->getCaptcha();

		$response = array();

		$response['id']  = $captcha->generate();
		$response['img'] = $captcha->getImgUrl() . $captcha->getId() . $captcha->getSuffix();
		
		$this->getResponse()->setBody(json_encode($response));
	}
}