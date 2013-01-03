<?php

class StepsController extends Zend_Controller_Action {
	
    public function step1Action() {
    	//$this->_helper->viewRenderer->setRender('steps');
    	$this->view->title = $this->translate('Create your profile');
    	$form = new Application_Form_Register_Step1();
    	
    	if($this->getRequest()->isPost()) {
			$response = $this->getResponse();
			if(!$form->isValid($this->getRequest()->getPost())) {
				
				if($form->getElement('year')->hasErrors() || $form->getElement('month')->hasErrors() || $form->getElement('day')->hasErrors()) {
					$form->getElement('date_error')->addError($this->translate('Invalid date'));
				}
				$this->view->form = $form;
				
			}else {
				$this->model('user')->fillStep1($this->view->user->user_id, $form->getValues());
				$this->reauth($this->view->user->user_id);
				$this->_redirect('steps/step2');
			}
		}else {
			$user_data = $this->getUserData();
			$form->populate($user_data['step1']);
			$this->view->form = $form;
		}
    }
    
    private function getUserData() {
    	$db = $this->model('profileoption');
	
	    $user_options = $db->getOptionsByUserId($this->view->user->user_id);
	    $user_option = array('step1' => array(), 'step2' => array(), 'step3' => array());
	    foreach($user_options as $u_o) {
	        	if($u_o['type'] == 'checkbox') {
	        		//для нескольлких значений
	        		$user_option[$u_o['section']]['profile_checkbox' . $u_o['profile_option_id']][] = $u_o['profile_option_value_id'];
	        	}else {
	        		$user_option[$u_o['section']]['profile_select' . $u_o['profile_option_id']] = $u_o['profile_option_value_id'];
	        	}
	    }

	    $user = $this->model('user')->getUser($this->view->user->user_id);
	    $languages = $this->model('user')->getLanguagesByUserId($this->view->user->user_id);
	    $user_languages = array();
	    $i = 0;
	    foreach($languages as $language) {
	        	$user_languages['languages']['language' . $i] = $language['all_language_id'];
	        	$user_languages['languages']['skill' . $i] = $language['skill_id'];
	        	$i++;
	    }
	    
	    $children = $this->model('user')->getChildrenByUserId($this->view->user->user_id);
	    $user_children = array();
	    $i = 0;
	    foreach($children as $child) {
	        	$user_children['children']['child' . $i] = array(
	        		'name'		=> $child['name'],
	        		'child_age'		=> $child['child_age'],
	        		'together'	=> $child['together'],
	        	);
	        	$i++;
	    }
	    $user_children['children']['childs'] = $user['childs'];
	    
	    $birth_date = explode('-', $user['birth_date']);
	
	    $step1 = array_merge($user, $user_option['step1'], $user_languages, $user_children, array('day' => $birth_date[2], 'month' => $birth_date[1],'year' => $birth_date[0]));
	    $step2 = $user_option['step2'];
	    $step3 = $user_option['step3'];
	        
	    return array('step1' => $step1, 'step2' => $step2, 'step3' => $step3);
    }
    
    public function step2Action() {
    	//$this->_helper->viewRenderer->setRender('steps');
    	$this->view->title = $this->translate('About You');
    	$form = new Application_Form_Register_Step2();
    	
    	if($this->getRequest()->isPost()) {
			$response = $this->getResponse();
			if(!$form->isValid($this->getRequest()->getPost())) {
				$this->view->form = $form;
			}else {
				$this->model('user')->fillStep2($this->view->user->user_id, $form->getValues());
				$this->reauth($this->view->user->user_id);
				$this->_redirect('steps/step3');
			}
		}else {
			$user_data = $this->getUserData();
			$form->populate($user_data['step2']);
			$this->view->form = $form;
		}
    }
    
    public function step3Action() {
    	//$this->_helper->viewRenderer->setRender('steps');
    	$this->view->title = $this->translate('About Your future partner');
    	$form = new Application_Form_Register_Step3();
    	
    	if($this->getRequest()->isPost()) {
			$response = $this->getResponse();
			if(!$form->isValid($this->getRequest()->getPost())) {
				$this->view->form = $form;
			}else {
				$this->model('user')->fillStep3($this->view->user->user_id, $form->getValues());
				$this->reauth($this->view->user->user_id);
				$this->model('recomendation')->generateForUser($this->view->user->user_id);
				$this->_redirect('gallery');
			}
		}else {
			$user_data = $this->getUserData();
			$form->populate($user_data['step3']);
			$this->view->form = $form;
		}
    }
    
    private function reauth($user_id) {
		$user = $this->model('user')->getUser($user_id);
		Zend_Auth::getInstance()->setStorage(new Zend_Auth_Storage_Session('site'))->clearIdentity();
		Zend_Auth::getInstance()->setStorage(new Zend_Auth_Storage_Session('site'))->getStorage()->write((object)$user);
	}
}