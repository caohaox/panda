<?php

class ErrorsController extends Zend_Controller_Action
{
    public function accessAction() {
    	
    }
    
    
    public function notfoundAction() {
    	$this->view->title = $this->translate('Page not found');
    }

    public function usernotfoundAction() {
    	$this->view->title = $this->translate('User not found');
    	
    	$this->_helper->viewRenderer->setRender('notfound');
    }
}

