<?php

class IndexController extends Zend_Controller_Action
{
    public function indexAction() {
        $this->view->headScript()->appendFile($this->view->baseUrl('js/jquery.randomtip.js'));
        
        $this->view->women = $this->model('user')->getRandomImages(10, 'onlyWomen');
        
        $this->view->men = $this->model('user')->getRandomImages(10, 'onlyMen');
    }

    
    //смена языка
	public function languageAction() {
		
		
    	$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout()->disableLayout();
		
		if($this->_getParam('language_id')) {
			$session = new Zend_Session_Namespace('site');
			$db = Zend_Registry::get('db');
			$query = $db->select()
						   ->from('language')
						   ->where('language_id = ?', $this->_getParam('language_id'));
			
			$language = $db->fetchRow($query);
			
			if($language) {
				Zend_Registry::set('language_id', $language['language_id']);
		
				$session->language = $language;
				setcookie('language_id', $language['language_id'], time() + 60 * 60 * 24 * 30, '/', $_SERVER['HTTP_HOST']);
			}
		}
	}
}

