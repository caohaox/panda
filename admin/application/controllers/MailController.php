<?php

class MailController extends Zend_Controller_Action {
	
	public function sendAction() {
		
		$form = new Application_Form_Mail();
		if($this->_getParam('user_id')) {
			$user = $this->model('users')->getUser($this->_getParam('user_id'));
			if($user) {
				$form->populate(array('email' => $user['email']));
			}
		}
		
		if($this->_getParam('redirect')) {
			$form->populate(array('redirect' => $this->_getParam('redirect')));
		}
		
		if($this->getRequest()->isPost()) {
			if($form->isValid($this->getRequest()->getPost())) {
				
				$sendMail = new Zend_Mail('UTF-8');
				$sendMail->setSubject($this->translate($form->getValue('title')));
				$sendMail->addTo($form->getValue('email'));
				$sendMail->setFrom(Admin_Config::get('admin_email'), Admin_Config::get('site_name'));
				$sendMail->setBodyHtml(html_entity_decode($form->getValue('text'), ENT_QUOTES, 'UTF-8'));
				$sendMail->send();
				
				$this->addFlash($this->translate('Message sent'));
				
				if($form->getValue('redirect')) {
					$this->_redirect($form->getValue('redirect'));
				}else $this->_redirect('index');
				
			}else $this->view->form = $form;
		}else $this->view->form = $form;
	}
}