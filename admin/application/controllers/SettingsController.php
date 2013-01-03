<?php
class SettingsController extends Zend_Controller_Action {
	
	public function indexAction() {
		$form = new Application_Form_Settings_Site();
		
		if($this->getRequest()->isPost()) {
			if($form->isValid($this->getRequest()->getPost())) {
				$db = new Application_Model_Settings();
				
				$db->updateSettings($form->getValues());
				$this->_helper->getHelper('FlashMessenger')->addMessage($this->translate('Settings saved'));
				$this->_redirect('settings');
				
			}else $this->view->form = $form;
		}else $this->view->form = $form;
	}
}