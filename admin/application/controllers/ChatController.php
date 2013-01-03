<?php

class ChatController extends Zend_Controller_Action {
	
	public function watchAction() {
		
		if($this->_getParam('user_1') && $this->_getParam('user_2')) {
			$this->view->chat = $this->model('chat')->getMessages($this->_getParam('user_1'), $this->_getParam('user_2'));
			
			$this->view->user_1 = $this->model('users')->getUser($this->_getParam('user_1'));
			$this->view->user_2 = $this->model('users')->getUser($this->_getParam('user_2'));
		}
	}
	
	
	public function removemessageAction() {
		
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout()->disableLayout();
		
		if($this->_getParam('message_id')) {
			$this->model('chat')->removeMessage($this->_getParam('message_id'));
			
			$this->addFlash($this->translate('Message was removed'));
		}
		
		
		if($this->_getParam('redirect')) {
			$this->_redirect($this->_getParam('redirect'));
		}else $this->_redirect('users/all');
	}
	
	
	public function editmessageAction() {
		if($this->_getParam('message_id')) {
			
			if($this->getRequest()->isPost()) {
				$this->model('chat')->editMessage($this->_getParam('message_id'), $this->_getParam('description'));
				
				$this->addFlash($this->translate('Message was updated'));
				
				if($this->_getParam('redirect')) {
					$this->_redirect($this->_getParam('redirect'));
				}else $this->_redirect('users/all');
			}else {
				$this->view->message_id = $this->_getParam('message_id');
				$message = $this->model('chat')->getMessage($this->_getParam('message_id'));
				
				$this->view->description = $message['description'];
				
				if($this->_getParam('redirect')) {
					$this->view->redirect = $this->_getParam('redirect');
				}else $this->view->redirect = '';
			}
		}
	}
}