<?php

class FriendsController extends Zend_Controller_Action {
	
	public function indexAction() {
		
		
		$this->view->friends = $this->model('friends')->getFriends($this->view->user->user_id);
		
	}
	
	
	public function autocompleteAction() {
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout()->disableLayout();
		$friends = array();
		if($this->_getParam('name')) {
			
			$friends = $this->model('friends')->findFriend($this->_getParam('name'), $this->view->user->user_id);
			
		}
		
		$this->getResponse()->setBody(json_encode($friends));
	}
	
	
	public function setAction() {
    	$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout()->disableLayout();
		
		$this->model('friends')->setFriendship($this->view->user->user_id, $this->_getParam('user_id'));
	
		$this->getResponse()->setBody('ok');
    }
    
    
    public function acceptAction() {
    	$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout()->disableLayout();
		
		$this->model('friends')->acceptFriendship($this->view->user->user_id, $this->_getParam('user_id'));
	
		$this->_redirect('friends');
    } 
    
    public function declineAction() {
    	$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout()->disableLayout();
		
		$this->model('friends')->declineFriendship($this->view->user->user_id, $this->_getParam('user_id'));
	
		$this->_redirect('friends');
    }
}

