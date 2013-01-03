<?php

class UserController extends Zend_Controller_Action {
	
    public function indexAction() {
    	if(!$this->_getParam('userId')) $this->_redirect('errors/usernotfound');
    	
        $user = $this->model('user')->getUser($this->_getParam('userId'));

        if(!$user['user_id']) {
        	$this->_redirect('errors/usernotfound');
        }
        
        $this->view->areFriends = $this->model('friends')->getFriendshipStatus($this->view->user->user_id, $this->_getParam('userId'));
        //var_dump($this->view->areFriends);
        $this->view->headLink()->appendStylesheet($this->view->baseUrl('js/pikachoose/css/bottom.css'));
        $this->view->headLink()->appendStylesheet($this->view->baseUrl('js/accordion/css/accordion.css'));
        $this->view->headScript()->appendFile($this->view->baseUrl('js/pikachoose/js/jquery.jcarousel.min.js'));
        $this->view->headScript()->appendFile($this->view->baseUrl('js/pikachoose/js/jquery.pikachoose.js'));
        $this->view->headScript()->appendFile($this->view->baseUrl('js/accordion/js/accordion.js'));
        
        $this->view->name = $user['fullname_eng'];
        $this->view->user_id = $user['user_id'];
		
        $images = $this->model('user')->getImages($this->_getParam('userId'));
        $this->view->status = $this->model('user')->getOnlineStatus($this->_getParam('userId'));
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
}

