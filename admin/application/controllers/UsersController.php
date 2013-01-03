<?php
class UsersController extends Zend_Controller_Action {
	
	public function allAction() {
		
		//$url_order => for a view
		//$order => for a model
		$url_order = array();
		$url_order['email'] = 'desc';
		$url_order['name'] = 'desc';
		$url_order['status'] = 'desc';
		$url_order['block'] = 'desc';
		
		if($this->_getParam('sort')) {
			$sort = $this->_getParam('sort');
			$this->view->sort = $this->_getParam('sort');
			if($this->_getParam('order')) {
				$order = $this->_getParam('order');
				$this->view->order = $this->_getParam('order');
				//if sort order is already DESC we make ASC in link for current sort parameter and on the other way round
				if($this->_getParam('order') == 'desc') {
					$url_order[$sort] = 'asc';
				}else {
					$url_order[$sort] = 'desc';
				}
			}else {
				$order = 'DESC';
				$this->view->order = 'DESC';
			}
		}else {
			$sort = 'user_id';
			$order = 'DESC';
			$this->view->sort = 'user_id';
			$this->view->order = 'DESC';
		}
		
		if($this->_getParam('sort') && $this->_getParam('order')) {
			$this->view->url = '/sort/' . $this->_getParam('sort') . '/order/' . $this->_getParam('order');
		}else $this->view->url = '';
		
		$this->view->sort_email = $this->view->baseUrl('users/all/sort/email/order/' . $url_order['email']);
		$this->view->sort_name = $this->view->baseUrl('users/all/sort/name/order/' . $url_order['name']);
		$this->view->sort_status = $this->view->baseUrl('users/all/sort/status/order/' . $url_order['status']);
		$this->view->sort_block = $this->view->baseUrl('users/all/sort/block/order/' . $url_order['block']);
		
		$db = new Application_Model_Users();
		$users = $db->getUsers($sort, $order);
		$total = $db->getTotalUsers();
		
		//start pagination
		$adapter = new Zend_Paginator_Adapter_DbSelect($users);
		$adapter->setRowCount($total);
		$paginator = new Zend_Paginator($adapter);
		$paginator->setItemCountPerPage(20);

		$paginator->setCurrentPageNumber($this->_getParam('page'));
		
		if($this->_getParam('page')) {
			$this->view->page = $this->_getParam('page');
		}else $this->view->page = 1;
		
		$this->view->paginator = $paginator;
	}
	
	
	public function editAction() {
		
		//if($this->_getParam('user_id')) {
			
			//$db = new Application_Model_Users();
			$db = $this->model('users');

			$form = new Application_Form_User_Edit();
			
			//$form->populate($db->getUserForEdit($this->_getParam('user_id')));
			
			$user_options = $this->model('profileoption')->getOptionsByUserId($this->_getParam('user_id'));
			//var_dump($user_options);
	        $user_option = array('step1' => array(), 'step2' => array(), 'step3' => array());
	        foreach($user_options as $u_o) {
	        	if($u_o['type'] == 'checkbox') {
	        		//для нескольлких значений
	        		$user_option[$u_o['section']]['profile_checkbox' . $u_o['profile_option_id']][] = $u_o['profile_option_value_id'];
	        	}else {
	        		$user_option[$u_o['section']]['profile_select' . $u_o['profile_option_id']] = $u_o['profile_option_value_id'];
	        	}
	        }

	        $user = $this->model('users')->getUserForEdit($this->_getParam('user_id'));
	        $languages = $this->model('users')->getLanguagesByUserId($this->_getParam('user_id'));
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
	       // var_dump($step2);
	        $form->populate(array('step1' => $step1, 'step2' => $step2, 'step3' => $step3));
			
			//$form->setAction($this->view->baseUrl('users/edit'));
			//if($this->_getParam('sort') && $this->_getParam('order')) {
				//$form->setAction($this->view->baseUrl('users/edit/sort/' . $this->_getParam('sort') . '/order/' . $this->_getParam('order')));
			//}else $form->setAction($this->view->baseUrl('users/edit'));
			
			if($this->getRequest()->isPost()) {
				if($form->isValid($this->getRequest()->getPost())) {
					
					$db->editUser($this->_getParam('user_id'), $form->getValues());
					$this->_helper->getHelper('FlashMessenger')->addMessage($this->translate('User updated'));
					
					if($this->_getParam('sort') && $this->_getParam('order')) {
						$this->_redirect('users/all/sort/' . $this->_getParam('sort') . '/order/' . $this->_getParam('order'));
					}else $this->_redirect('users/all/');
					
				}else {
					
					$this->view->form = $form;
				}
			}else $this->view->form = $form;
		//}
	}
	
	
	public function blockAction() {
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout()->disableLayout();
		
		if($this->_getParam('user_id')) {
			$db = new Application_Model_Users();
			$db->blockUser($this->_getParam('user_id'));
		}
		
		if($this->_getParam('sort') && $this->_getParam('order')) {
			$this->_redirect('users/all/sort/' . $this->_getParam('sort') . '/order/' . $this->_getParam('order'));
		}else $this->_redirect('users/all/');
	}
	
	
	public function unblockAction() {
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout()->disableLayout();
		
		if($this->_getParam('user_id')) {
			$db = new Application_Model_Users();
			$db->unblockUser($this->_getParam('user_id'));
		}
		
		if($this->_getParam('sort') && $this->_getParam('order')) {
			$this->_redirect('users/all/sort/' . $this->_getParam('sort') . '/order/' . $this->_getParam('order'));
		}else $this->_redirect('users/all/');
	}
	
	public function recomendationsAction() {

		if($this->_getParam('user_id')) {
			
			$this->view->current_user = $this->model('users')->getUser($this->_getParam('user_id'));

			$this->view->recomendations = $this->model('recomendation')->getRecomendations($this->_getParam('user_id'));
			
			
		}
	}
	
	public function removerecomendationAction() {
		
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout()->disableLayout();
		
		if($this->_getParam('recomendation_id')) {
			
			$this->model('recomendation')->removeRecomendation($this->_getParam('recomendation_id'));
			
			$this->addFlash($this->translate('Recommendation was removed'));
		}
		
		if($this->_getParam('redirect')) {
			$this->_redirect($this->_getParam('redirect'));
		}else $this->_redirect('users/recomendations');
	}
	
	public function addrecomendationAction() {
		
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout()->disableLayout();
		
		if($this->_getParam('addrec_id') && $this->_getParam('addrec')) {
			
			$this->model('recomendation')->addRecomendation($this->_getParam('user_id'), $this->_getParam('addrec_id'));
			
			$this->addFlash($this->translate('Recommendation was added'));
		}
		
		if($this->_getParam('redirect')) {
			$this->_redirect($this->_getParam('redirect'));
		}else $this->_redirect('users/recomendations');
	}
	
	
	public function autocompleteAction() {
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout()->disableLayout();
		$users = array();
		if($this->_getParam('name')) {
			
			$data = $this->model('users')->findUser($this->_getParam('name'));
			
			foreach($data as $data) {
				$users[] = array(
					'name'		=> $data['fullname'],
					'user_id'	=> $data['user_id'],
				);
			}
		}
		
		$this->getResponse()->setBody(json_encode($users));
	}
	
	public function chatsAction() {
		if($this->_getParam('user_id')) {
			
			$this->view->current_user = $this->model('users')->getUser($this->_getParam('user_id'));
			
			$this->view->chats = $this->model('chat')->getAllChats($this->_getParam('user_id'));
			
		}
	}
}