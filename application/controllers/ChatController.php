<?php

class ChatController extends Zend_Controller_Action {
	
	/*private $chat;
	
	public function init() {
		$this->chat = Site_UserConsultation::getInstance($this->view->user->user_id);
	}
	*/
	public function indexAction() {
		
		$this->view->chats = $this->model('chat')->getAllChats($this->view->user->user_id);
		
		
		$this->view->messages = array();
		foreach($this->model('chat')->getUnreadMessagesByUsers($this->view->user->user_id) as $row) {
			$this->view->messages[$row['from_user_id']] = $row;
		}
	}
	
	
	public function chatAction() {
		
		if($this->_getParam('user') && ($partner = $this->model('user')->getUser($this->_getParam('user')))) {
			$this->view->partner = $partner;
			
			$chat = $this->model('chat');
			
			$this->view->messages = $chat->getMessages($this->_getParam('user'), $this->view->user->user_id);
			
			$newMessages = $chat->getUnreadMessagesByUser($this->view->user->user_id, $this->_getParam('user'));
			foreach($newMessages as $newMessage) {
				$chat->setRead($newMessage['message_id']);
			}
			
		}else $this->_redirect('chat');
	}
	
	
	/*public function startAction() {
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout()->disableLayout();
		
		if($this->_getParam('friend_search_id')) {
			//проверяем на наличие чата с этим пользователем]
			//$chat_id = $this->model('chat')->getChatByPartnerId($this->_getParam('user'), $this->view->user->user_id);
			
			//if(!$chat_id) {
				//$this->model('chat')->addChat($this->_getParam('user'), $this->view->user->user_id);
			//}
		}
		
		$this->_redirect($this->view->baseUrl('chat'));
	}*/
	
	
	public function addmessageAction() {
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout()->disableLayout();
		$output = array();
		if($this->_getParam('partner_id') && $this->_getParam('message')) {
			if($this->model('friends')->checkFriends($this->_getParam('partner_id'), $this->view->user->user_id)) {
				$response = $this->model('chat')->addMessage($this->view->user->user_id, $this->_getParam('partner_id'), $this->_getParam('message'));
				$output['success'] = $response['date'];
				$output['message'] = $response['message'];
				$output['message_id'] = $response['message_id'];
			}
		}
		
		$this->getResponse()->setBody(json_encode($output));
	}
	
	public function getnewmessagesAction() {
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout()->disableLayout();
		
		$output = array();
		if($this->_getParam('partner_id')) {
			$messages = $this->model('chat')->getUnreadMessagesByUser($this->view->user->user_id, $this->_getParam('partner_id'));
			if($messages && $this->model('friends')->checkFriends($this->_getParam('partner_id'), $this->view->user->user_id)) {
				if(count($messages) > 1) {
					$output['messages'] = $messages;
				}else {
					$output['message'] = end($messages);
				}
			}
		}
		
		$this->getResponse()->setBody(json_encode($output));
	}
	
	
	public function setreadAction() {
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout()->disableLayout();
		
		$output = array();
		if($this->_getParam('message_id') && $this->model('chat')->checkMessage($this->_getParam('message_id'), $this->view->user->user_id)) {
			if(count($this->_getParam('message_id')) > 1) {
				foreach($this->_getParam('message_id') as $message_id) {
					$this->model('chat')->setRead($message_id);
				}
			}else $this->model('chat')->setRead($this->_getParam('message_id'));
		}
		
		$this->getResponse()->setBody(json_encode($output));
	}
	
	
	public function spamAction() {
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout()->disableLayout();
		
		$output = array();
		if($this->_getParam('message_id') && $this->model('chat')->checkMessage($this->_getParam('message_id'), $this->view->user->user_id)) {
			
			$this->model('chat')->spamReport($this->_getParam('message_id'), $this->_getParam('type'), $this->_getParam('text'));
			$output['success'] = $this->translate('Report sent');
		}
		
		$this->getResponse()->setBody(json_encode($output));
	}
}

