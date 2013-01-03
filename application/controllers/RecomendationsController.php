<?php

class RecomendationsController extends Zend_Controller_Action {
	
	
	public function indexAction() {
		$date = new Zend_Date();
		if($date->isLater(Site_Config::get('next_recomendation_date'))) {
			$this->model('recomendation')->setNextGenerateDate();
			$this->model('recomendation')->addAllRecomendationsToArchive();
			$this->model('recomendation')->generate();
		}
		
		$this->view->users = $this->model('recomendation')->getRecomendations($this->view->user->user_id);

	}
	
	
	public function declineAction() {
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout()->disableLayout();
		if($this->_getParam('user_id')) {
			$this->model('recomendation')->delete($this->view->user->user_id, $this->_getParam('user_id'));
		}
		
		$this->_redirect('recomendations');
	}
	
	public function acceptAction() {
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout()->disableLayout();
		if($this->_getParam('user_id')) {
			$this->model('recomendation')->delete($this->view->user->user_id, $this->_getParam('user_id'));
			$this->model('friends')->setFriendship($this->view->user->user_id, $this->_getParam('user_id'));
			//тут можно сделать сообщение
			$this->addFlash($this->translate('Friendship request sent'));
		}
		
		//$this->_redirect('recomendations');
		$this->getResponse()->setBody('ok');
	}
}

