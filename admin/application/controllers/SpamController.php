<?php

class SpamController extends Zend_Controller_Action {
	
	public function allAction() {
		$spams = $this->model('spam')->getSpams();
		$total = $this->model('spam')->getTotalSpams();
		
		//start pagination
		$adapter = new Zend_Paginator_Adapter_DbSelect($spams);
		$adapter->setRowCount($total);
		$paginator = new Zend_Paginator($adapter);
		$paginator->setItemCountPerPage(20);

		$paginator->setCurrentPageNumber($this->_getParam('page'));
			
		$this->view->paginator = $paginator;
	}
	
	
	public function removeAction() {
		
		$this->model('spam')->removeSpam($this->_getParam('spam_id'));
		
		$this->addFlash($this->translate('Entry was removed'));
		$this->_redirect('spam/all');
	}
}