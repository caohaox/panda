<?php

class PageController extends Zend_Controller_Action {
	
    public function indexAction() {
        if($this->_getParam('pageName')) {        	
        	$page = $this->model('page')->getPageBySeoUrl($this->_getParam('pageName'));
        	if(!$page) {
        		$this->_redirect('errors/notfound');
        	}else {
        		$this->_helper->viewRenderer->setRender('templates/' . $page['template_id']);
        		$this->view->page = $page;
        		$this->view->headTitle($this->view->escape($page['title']));
        		
        		if($this->view->escape($page['meta_keywords'])) {
        			$this->view->headMeta()->appendName('keywords', $this->view->escape($page['meta_keywords']));
        		}
        		
        		if($this->view->escape($page['meta_description'])) {
        			$this->view->headMeta()->appendName('description', $this->view->escape($page['meta_description']));
        		}
        	}
        }else $this->_redirect('errors/notfound');
    }
}