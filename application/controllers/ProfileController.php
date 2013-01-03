<?php

class ProfileController extends Zend_Controller_Action {
	
    public function indexAction() {
    	
        $this->view->headLink()->appendStylesheet($this->view->baseUrl('js/pikachoose/css/bottom.css'));
        $this->view->headLink()->appendStylesheet($this->view->baseUrl('js/accordion/css/accordion.css'));
        $this->view->headScript()->appendFile($this->view->baseUrl('js/pikachoose/js/jquery.jcarousel.min.js'));
        $this->view->headScript()->appendFile($this->view->baseUrl('js/pikachoose/js/jquery.pikachoose.js'));
        $this->view->headScript()->appendFile($this->view->baseUrl('js/accordion/js/accordion.js'));
        
        $images = $this->model('user')->getImages($this->view->user->user_id);
        $this->view->images = array();
         
        if(!$images) {
        	$this->view->images[] = array(
				'big'		=> Site_Image::resize('', 500, 500),
				'middle'	=> Site_Image::resize('', 271, 271),
				'little'	=> Site_Image::resize('', 67, 67),
			);
        }else {
	        foreach($images as $image) {
				$this->view->images[] = array(
					'big'		=> Site_Image::resize($image['image'], 500, 500),
					'middle'	=> Site_Image::resize($image['image'], 271, 271),
					'little'	=> Site_Image::resize($image['image'], 67, 67),
				);
	        }
        }
    }
    
    
    public function editAction() {
    	$this->view->headLink()->appendStylesheet($this->view->baseUrl('js/pikachoose/css/bottom.css'));
        $this->view->headLink()->appendStylesheet($this->view->baseUrl('js/accordion/css/accordion.css'));
        $this->view->headScript()->appendFile($this->view->baseUrl('js/pikachoose/js/jquery.jcarousel.min.js'));
        $this->view->headScript()->appendFile($this->view->baseUrl('js/pikachoose/js/jquery.pikachoose.js'));
        $this->view->headScript()->appendFile($this->view->baseUrl('js/accordion/js/accordion.js'));
        $this->view->headScript()->appendFile($this->view->baseUrl('js/ajaxupload.js'));

        $user = $this->model('user')->getUser($this->view->user->user_id);
        $this->view->main_image = $user['image'];
        
        $images = $this->model('user')->getImages($this->view->user->user_id);
        $this->view->images = array();
        
        if($images) {
	        foreach($images as $image) {
				$this->view->images[] = array(
					'big'		=> Site_Image::resize($image['image'], 271, 271),
					'thumb'		=> Site_Image::resize($image['image'], 67, 67),
					'image'		=> $image['image'],
					'image_id'	=> $image['image_id'],
				);
	        }
        }
		
        $form = new Application_Form_Profile_Profile();
        
        if($this->getRequest()->isPost()) {
			if(!$form->isValid($this->getRequest()->getPost())) {
				if($form->getSubForm('step1')->getElement('year')->hasErrors() || $form->getSubForm('step1')->getElement('month')->hasErrors() || $form->getSubForm('step1')->getElement('day')->hasErrors()) {
					$form->getSubForm('step1')->getElement('date_error')->addError($this->translate('Invalid date'));
				}
				$this->view->form = $form;
			}else {
				$this->model('user')->update($this->view->user->user_id, $form->getValues());
				$this->reauth($this->view->user->user_id);
				$this->_redirect('profile');
			}
		}else {
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
	        $birth_date = explode('-', $user['birth_date']);
	
	        $step1 = array_merge($user, $user_option['step1'], $user_languages, array('day' => $birth_date[2], 'month' => $birth_date[1],'year' => $birth_date[0]));
	        $step2 = $user_option['step2'];
	        $step3 = $user_option['step3'];
	        
	        $form->populate(array('step1' => $step1, 'step2' => $step2, 'step3' => $step3));
	        
	        $this->view->form = $form;
		}
    }
    
    
    private function reauth($user_id) {
		$user = $this->model('user')->getUser($user_id);
		Zend_Auth::getInstance()->setStorage(new Zend_Auth_Storage_Session('site'))->clearIdentity();
		Zend_Auth::getInstance()->setStorage(new Zend_Auth_Storage_Session('site'))->getStorage()->write((object)$user);
	}
	
	public function removeimageAction() {
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout()->disableLayout();
		if($this->_getParam('image_id')) {
			$this->model('user')->removeImage($this->view->user->user_id, $this->_getParam('image_id'));
		}
		$this->getResponse()->setBody('ok');
	}
	
	public function setmainimageAction() {
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout()->disableLayout();
		if($this->_getParam('image')) {
			$this->model('user')->setMainImage($this->view->user->user_id, $this->_getParam('image'));
		}
		$this->getResponse()->setBody('ok');
	}
	
	
	public function settingsAction() {
		$this->view->headLink()->appendStylesheet($this->view->baseUrl('js/pikachoose/css/bottom.css'));

        $this->view->headScript()->appendFile($this->view->baseUrl('js/pikachoose/js/jquery.jcarousel.min.js'));
        $this->view->headScript()->appendFile($this->view->baseUrl('js/pikachoose/js/jquery.pikachoose.js'));

		$form = new Application_Form_Profile_Settings();
        
        if($this->getRequest()->isPost()) {
			if(!$form->isValid($this->getRequest()->getPost())) {
				$this->view->form = $form;
			}else {
				$this->model('user')->updateSettings($this->view->user->user_id, $form->getValues());
				$this->reauth($this->view->user->user_id);
				$this->_redirect('profile');
			}
		}else {
	        
	        $user = $this->model('user')->getUser($this->view->user->user_id);
	        
	        $form->populate($user);
	        
	        $this->view->form = $form;
		}
	}
	
	
	public function imonlineAction() {
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout()->disableLayout();
	}
}

