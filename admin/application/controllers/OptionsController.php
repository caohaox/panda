<?php

class OptionsController extends Zend_Controller_Action {
	
	public function allAction() {
		$options = $this->model('profileoption')->getOptions();
		$total = $this->model('profileoption')->getTotalOptions();
		
		//start pagination
		$adapter = new Zend_Paginator_Adapter_DbSelect($options);
		$adapter->setRowCount($total);
		$paginator = new Zend_Paginator($adapter);
		$paginator->setItemCountPerPage(20);

		$paginator->setCurrentPageNumber($this->_getParam('page'));
			
		$this->view->paginator = $paginator;
	}
	
	
	public function addAction() {
		
		$this->view->title = $this->translate('Add option');
		$form = new Application_Form_Option_Add();

		if($this->getRequest()->isPost()) {
			if($form->isValid($this->getRequest()->getPost())) {

				$this->model('profileoption')->addOption($form->getValues());
				
				$this->addFlash($this->translate('Option added'));
				$this->_redirect('options/all');
			}else $this->view->form = $form;
		}else $this->view->form = $form;
	}
	
	
	public function editAction() {
		
		if($this->_getParam('profile_option_id')) {
			
			$this->view->title = $this->translate('Edit option');
			
			$this->_helper->viewRenderer->setRender('add');

			$form = new Application_Form_Option_Add();
			
			$form->setAction($this->view->baseUrl('options/edit'));
			$profile_option_id = new Zend_Form_Element_Hidden('profile_option_id');
			$profile_option_id->addFilter('digits');
			$form->addElements(array($profile_option_id));

			if($this->getRequest()->isPost()) {
				if($form->isValid($this->getRequest()->getPost())) {
					
					$this->model('profileoption')->editOption($form->getValues());
					$this->addFlash($this->translate('Option updated'));
					$this->_redirect('options/all');
					
				}else $this->view->form = $form;
			}else {
				$form->populate($this->model('profileoption')->getOption($this->_getParam('profile_option_id')));
				$this->view->form = $form;
			}
		}
	}
	
	public function addvalueAction() {
		if($this->_getParam('profile_option_id')) {
			
			$optionName = $this->model('profileoption')->getOptionCurrentName($this->_getParam('profile_option_id'));
			
			$this->view->title = $this->translate('Add value for option') . ' "' . $optionName . '"';
			$form = new Application_Form_Option_Addvalue();

			if($this->getRequest()->isPost()) {
				if($form->isValid($this->getRequest()->getPost())) {
	
					$this->model('profileoption')->addOptionValue($form->getValues());
					
					$this->addFlash($this->translate('Option value added'));
					$this->_redirect('options/values/profile_option_id/' . $this->_getParam('profile_option_id'));
				}else $this->view->form = $form;
			}else {
				$form->populate(array('profile_option_id' => $this->_getParam('profile_option_id')));
				$this->view->form = $form;
			}
		}
	}
	
	public function editvalueAction() {
		if($this->_getParam('profile_option_value_id')) {
			
			$this->_helper->viewRenderer->setRender('addvalue');
			
			$this->view->title = $this->translate('Edit option value');
			$form = new Application_Form_Option_Addvalue();

			$form->setAction($this->view->baseUrl('options/editvalue'));
			$profile_option_value_id = new Zend_Form_Element_Hidden('profile_option_value_id');
			$profile_option_value_id->addFilter('digits');
			$form->addElements(array($profile_option_value_id));
			
			if($this->getRequest()->isPost()) {
				if($form->isValid($this->getRequest()->getPost())) {
	
					$profile_option_id = $this->model('profileoption')->editOptionValue($form->getValues());

					$this->addFlash($this->translate('Option value updated'));
					$this->_redirect('options/values/profile_option_id/' . $profile_option_id);
				}else $this->view->form = $form;
			}else {
				$form->populate($this->model('profileoption')->getOptionValue($this->_getParam('profile_option_value_id')));
				$this->view->form = $form;
			}
		}
	}
	
	
	public function valuesAction() {
		if($this->_getParam('profile_option_id')) {
			$this->view->profile_option_id = $this->_getParam('profile_option_id');
			$optionName = $this->model('profileoption')->getOptionCurrentName($this->_getParam('profile_option_id'));
			
			$this->view->title = $this->translate('Values for option') . ' "' . $optionName . '"';
			
			$values = $this->model('profileoption')->getValues($this->_getParam('profile_option_id'));

			$this->view->values = $values;
		}
	}
	
	public function removeAction() {
		
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout()->disableLayout();
		
		if($this->_getParam('profile_option_id')) {

			$profile_option_id = $this->model('profileoption')->removeOption($this->_getParam('profile_option_id'));
			$this->addFlash($this->translate('Option removed'));
			$this->_redirect('options/all');
		}
	}
	
	public function removevalueAction() {
		
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout()->disableLayout();
		
		if($this->_getParam('profile_option_value_id')) {

			$profile_option_id = $this->model('profileoption')->removeOptionValue($this->_getParam('profile_option_value_id'));
			$this->addFlash($this->translate('Value removed'));
			$this->_redirect('options/values/profile_option_id/' . $profile_option_id);
		}
	}
	
	
	public function getvaluesAction() {
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout()->disableLayout();
		
		$output = '';
		if($this->_getParam('profile_option_id')) {
			$values = $this->model('profileoption')->getValues($this->_getParam('profile_option_id'));
			$output .= '<option value=""></option>';
			foreach($values as $value) {
				$output .= '<option value="' . $value['profile_option_value_id'] . '">' . $value['name'] . '</option>';
			}
		}
		
		$this->getResponse()->setBody($output);
	}
}