<?php

class RequestsController extends Zend_Controller_Action {
	
	public function indexAction() {
		
		$this->view->requests = $this->model('friends')->getRequests($this->view->user->user_id);
		
		
		
	}
}

