<?php 
class Application_Form_Register_Forget extends Zend_Form {
	public function __construct() {
		
		$this->setDisableLoadDefaultDecorators(true);
 
        $this->addDecorator('FormElements')
        	 ->addDecorator('Form');
        	 
		$view = $this->getView();
		
		$translate = Zend_Registry::get('translate');
		
		$this->setName('forget');
		$this->setAction($view->baseUrl('register/forget'));
		
		$email = new Zend_Form_Element_Text('email');
		$email->setDecorators(array('ViewHelper', new Site_Form_Decorator_Text()));
		$email->setLabel($translate->_('Email'));
		$email->setRequired(true);
		$email->addValidator('EmailAddress');
		$email->addValidator(new Zend_Validate_StringLength(3, 64));
		$email->addFilter('StringTrim');

		
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setDecorators(array('ViewHelper', new Site_Form_Decorator_Submit()));
		$submit->setLabel($translate->_('Next'));
		
		$this->addElements(array($email, $submit));
		
	}
}