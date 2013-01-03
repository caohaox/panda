<?php

class GalleryController extends Zend_Controller_Action
{
    public function indexAction() {
    	
    	$current_options = (array)$this->_getParam('option');
    	$this->view->current_options = array();
    	
    	$data = array();
    	
    	$profile_db = $this->model('profileoption');
    	$profile_options = $profile_db->getOptions(array('sections' => array('step3')));
    	
    	$this->view->profile_options = array();
    	foreach($profile_options as $profile_option) {
    		if($profile_option['profile_option_id'] == Zend_Registry::get('partner_age')) continue;
    		$url = '?';
		    if($this->_getParam('option')) {
		    	foreach($this->_getParam('option') as $key => $val) {
		    		if($key != $profile_option['profile_option_id']) {
		    			if($url == '?') {
		    				$url .= 'option[' . $this->view->escape($key) . ']=' . (int)$val;
		    			}else {
		    				$url .= '&option[' . $this->view->escape($key) . ']=' . (int)$val;
		    			}
		    		}
		    	}
		    }
    		
    		$values = array();
    		foreach($profile_db->getValues($profile_option['profile_option_id']) as $option_value) {
    			
    			if(isset($current_options[$profile_option['profile_option_id']]) && $current_options[$profile_option['profile_option_id']] == $option_value['profile_option_value_id']) {
    				$this->view->current_options[$profile_option['name']] = array('value' => $option_value['name']);
    				$this->view->current_options[$profile_option['name']]['remove'] = $url;
    			}

    			
		    	if($url == '?') {
		    		$url_ = $url . 'option[' . $profile_option['profile_option_id'] . ']=' . $option_value['profile_option_value_id'];
		    	}else $url_ = $url . '&option[' . $profile_option['profile_option_id'] . ']=' . $option_value['profile_option_value_id'];
		    	
    			$values[] = array(
    				'name'	=> $option_value['name'],
    				'href'	=> $this->view->baseUrl('gallery/index' . $url_),
    			);
    		}
    		
    		//не имеет значения
    		$values[] = array(
    			'name'	=> $this->translate('Does not matter'),
    			'href'	=> $this->view->baseUrl('gallery/index' . $url),
    		);
    		
    		
    		
    		$this->view->profile_options[] = array(
    			'name'					=> $profile_option['name'],
    			'profile_option_id'		=> $profile_option['profile_option_id'],
    			'profile_option_values'	=> $values,
    		);
    	}
    	
    	//возраст от
		$this->view->ageFrom = array();
		$url = '?';
		if($this->_getParam('option')) {
			foreach($this->_getParam('option') as $key => $val) {
			    if($key != 'age_from') {
				    if($url == '?') {
				    	$url .= 'option[' . $this->view->escape($key) . ']=' . (int)$val;
				    }else {
				    	$url .= '&option[' . $this->view->escape($key) . ']=' . (int)$val;
				    }
				}
			}
		}	
				
		$this->view->ageFrom[0] = $this->view->baseUrl('gallery/index' . $url);
		for($i=18;$i<100;$i++) {
			if($url == '?') {
		    	$url_ = $url . 'option[age_from]=' . $i;
		    }else $url_ = $url . '&option[age_from]=' . $i;
		    
			$this->view->ageFrom[$i] = $this->view->baseUrl('gallery/index' . $url_);
		}
		if(isset($current_options['age_from']) || isset($current_options['age_to'])) {
			
			$url = '?';
			if($this->_getParam('option')) {
				foreach($this->_getParam('option') as $key => $val) {
				    if($key != 'age_from' && $key != 'age_to') {
					    if($url == '?') {
					    	$url .= 'option[' . $this->view->escape($key) . ']=' . (int)$val;
					    }else {
					    	$url .= '&option[' . $this->view->escape($key) . ']=' . (int)$val;
					    }
					}
				}
			}
			$this->view->current_options[$this->translate('Age')]['remove'] = $this->view->baseUrl('gallery/index' . $url);
			
			$this->view->current_options[$this->translate('Age')]['value'] = '';
			if(isset($current_options['age_from']) && $current_options['age_from']) $this->view->current_options[$this->translate('Age')]['value'] .= $current_options['age_from'];
			$this->view->current_options[$this->translate('Age')]['value'] .= ' - ';
			if(isset($current_options['age_to']) && $current_options['age_to']) $this->view->current_options[$this->translate('Age')]['value'] .= $current_options['age_to'];
		}

		//возраст до
		$this->view->ageTo = array();
		$url = '?';
		if($this->_getParam('option')) {
			foreach($this->_getParam('option') as $key => $val) {
			    if($key != 'age_to') {
				    if($url == '?') {
				    	$url .= 'option[' . $this->view->escape($key) . ']=' . (int)$val;
				    }else {
				    	$url .= '&option[' . $this->view->escape($key) . ']=' . (int)$val;
				    }
				}
			}
		}
		$this->view->ageTo[0] = $this->view->baseUrl('gallery/index' . $url);
		
		for($i=18;$i<100;$i++) {
			if($url == '?') {
		    	$url_ = $url . 'option[age_to]=' . $i;
		    }else $url_ = $url . '&option[age_to]=' . $i;
		    
			$this->view->ageTo[$i] = $this->view->baseUrl('gallery/index' . $url_);
		}
		

    	if($current_options) $data['options'] = $current_options;
    	
    	if($this->view->user->is_man) {
    		$data['onlyWomen'] = true;
    	}else $data['onlyMen'] = true;
    	

    	$users = $this->model('user')->getUsers($data);
    	$total = $this->model('user')->getTotalUsers($data);
    	
        $adapter = new Zend_Paginator_Adapter_DbSelect($users);
		$adapter->setRowCount($total);
		$paginator = new Zend_Paginator($adapter);
		$paginator->setItemCountPerPage(Site_Config::get('item_quantity'));

		$paginator->setCurrentPageNumber($this->_getParam('page'));

		$this->view->users = $paginator;
    }
}

