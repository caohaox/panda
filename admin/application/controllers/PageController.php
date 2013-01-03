<?php
class PageController extends Zend_Controller_Action {
	
	public function allAction() {
		
		$db = new Application_Model_Page();
		$page = $db->getPages();
		$total = $db->getTotalPages();
		
		//start pagination
		$adapter = new Zend_Paginator_Adapter_DbSelect($page);
		$adapter->setRowCount($total);
		$paginator = new Zend_Paginator($adapter);
		$paginator->setItemCountPerPage(20);

		$paginator->setCurrentPageNumber($this->_getParam('page'));
			
		$this->view->paginator = $paginator;
	}
	
	
	public function addAction() {
		
		$form = new Application_Form_Page_Add();
		
		
		$this->view->headLink()->appendStylesheet($this->view->baseUrl('js/ui/themes/ui-lightness/ui.all.css'));
		
		if($this->getRequest()->isPost()) {
			if($form->isValid($this->getRequest()->getPost())) {
				$db = new Application_Model_Page();
				$db->addPage($form->getValues());
				
				$this->addFlash($this->translate('Page added'));
				$this->_redirect('page/all');
			}else $this->view->form = $form;
		}else $this->view->form = $form;
	}
	
	
	public function editAction() {
		
		if($this->_getParam('page_id')) {
			$this->_helper->viewRenderer->setRender('add');
			
			$db = new Application_Model_Page();
			
			$form = new Application_Form_Page_Add();
			
			$form->setAction($this->view->baseUrl('page/edit'));
			$page_id = new Zend_Form_Element_Hidden('page_id');
			$page_id->addFilter('digits');
			$form->addElements(array($page_id));
			
			$this->view->headLink()->appendStylesheet($this->view->baseUrl('js/ui/themes/ui-lightness/ui.all.css'));
			
			if($this->getRequest()->isPost()) {
				if($form->isValid($this->getRequest()->getPost())) {
					
					$db->editPage($form->getValues());
					$this->addFlash($this->translate('Page updated'));
					$this->_redirect('page/all');
					
				}else $this->view->form = $form;
			}else {
				$form->populate($db->getPage($this->_getParam('page_id')));
				$this->view->form = $form;
			}
		}
	}
	
	
	public function removeAction() {
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout()->disableLayout();
		
		if($this->_getParam('page_id')) {
			$db = new Application_Model_Page();
			$db->removePage($this->_getParam('page_id'));
			$this->_helper->getHelper('FlashMessenger')->addMessage($this->translate('Page removed'));
		}
		$this->_redirect('page/all');
	}
}