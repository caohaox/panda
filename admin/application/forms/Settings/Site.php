<?php 
class Application_Form_Settings_Site extends Zend_Form {
	
	public function init() {
		$this->setDisableLoadDefaultDecorators(true);
 
        $this->addDecorator('FormElements')
        	 ->addDecorator('Form');
        	 
		$view = $this->getView();
		$translate = Zend_Registry::get('translate');
		
		$this->setName('site');
		$this->setAction($view->baseUrl('settings/index'));
		$this->setMethod('post');	

		$db = new Application_Model_Settings();
		$settings = $db->getSettings('site');

		foreach($settings as $setting) {
			$newElement = new Zend_Form_Element_Text($setting['setting_name']);
			$newElement->setLabel($setting['label']);
			$newElement->setValue($setting['value']);
			$newElement->setDecorators(array('ViewHelper', new Admin_Form_Decorator_Text()));
			$newElement->addFilter('StripTags');
			$newElement->addFilter('StringTrim');
			$this->addElement($newElement);
		}
		
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setName($translate->_('Save'));
		$submit->setDecorators(array('ViewHelper', new Admin_Form_Decorator_Submit()));
		$this->addElement($submit);
	}
	
	
}