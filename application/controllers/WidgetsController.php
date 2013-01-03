<?php

class WidgetsController extends Zend_Controller_Action
{
    public function welcomegentlemensAction() {
    	$this->view->random_users = $this->model('user')->getRandomImages(10, 'onlyWomen');
    	
    }
    
    public function welcomeladiesAction() {
    	$this->view->random_users = $this->model('user')->getRandomImages(10, 'onlyMen');
    }
    
    public function registerladiesAction() {
    	
    }
    
    public function registergentlemensAction() {
    	
    }
    
    public function accountmenuAction() {
    	$this->view->my_profile = $this->translate('My profile');
    	$this->view->my_recomendations = $this->translate('My recomendations');
    	$this->view->my_requests = $this->translate('My requests');
    	$this->view->my_friends = $this->translate('My friends');
    	$this->view->my_messages = $this->translate('My messages');
    	$this->view->my_settings = $this->translate('My settings');
    	
    	$this->view->messages = $this->model('chat')->getTotalUnreadMessages($this->view->user->user_id);
		
    	$this->view->requests = $this->model('friends')->getTotalRequests($this->view->user->user_id);
    	
    	$this->view->recomendations = $this->model('recomendation')->getTotalRecomendations($this->view->user->user_id);
    }
    
    public function menuAction() {
    	
    }
    
    public function loginAction() {
    	if(!$this->view->user) {
    		$cache = Zend_Registry::get('cache');
    		$form = $cache->load('login_form' . Zend_Registry::get('language_id'));
    		if(!$form) {
    			$form = new Application_Form_Login();
    			$cache->save($form->render(), 'login_form' . Zend_Registry::get('language_id'));
	    		$this->view->form = $form;
	    	}else {
    			$this->view->form = $form;
    		}
    	}else $this->view->form = '';
    }
    
    
    public function profileinfoAction() {
    	$param = Zend_Controller_Front::getInstance()->getRequest()->getParam('userId');
    	if($param) {
    		$user_id = $param;
    		
    		$user = $this->model('user')->getUser($user_id);
    		
    		//$this->view->telephone = $user['telephone'];
    		//$this->view->skype = $user['skype'];
    		
    		$date = new Zend_Date($user['birth_date']);
    		$this->view->birth_date = $date->toString('YYYY.MM.dd');
    		
    		$this->view->langauges = $this->model('user')->getLanguagesByUserId($user_id);
    		
    	}elseif($this->view->user->user_id) {
    		$user_id = $this->view->user->user_id;
    				
			//$this->view->telephone = $this->view->user->telephone;
    		//$this->view->skype = $this->view->user->skype;
    		
    		$date = new Zend_Date($this->view->user->birth_date);
    		$this->view->birth_date = $date->toString('YYYY.MM.dd');
    		
    		$this->view->langauges = $this->model('user')->getLanguagesByUserId($user_id);
    	}
    	
    	$return = array();
    	
    	if($user_id) {
    		$options = $this->model('profileoption')->getOptionsByUserId($user_id);
    		foreach($options as $option) {
    			$return[$option['section']][] = $option;
    		}
    	}
    	
    	$this->view->options = $return;
    	
    	
    	
    }
    
    public function notificationAction() {
    	$this->view->data = $this->model('page')->getPageById(Zend_Registry::get('notification_area'));
    }
    
    
    public function spamAction() {
    	$form = new Application_Form_Spam();
    	
    	$this->view->form = $form;
    }
}

