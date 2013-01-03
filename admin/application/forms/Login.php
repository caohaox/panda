<?php 
class Application_Form_Login extends Zend_Form {
	
	public function init() {
		$this->setDisableLoadDefaultDecorators(true);
 
        $this->addDecorator('FormElements')
        	 ->addDecorator('Form');
        	 
		$view = $this->getView();
		$translate = Zend_Registry::get('translate');
		
		$this->setName('login_form');
		$this->setAction($view->baseUrl('login/index'));
	
		$login = new Zend_Form_Element_Text('login');
		$login->setLabel($translate->_('Login'));
		$login->setDecorators(array('ViewHelper', 'Label', 'Errors'));
		$login->setRequired(true);
		$login->addValidator('Alnum');
		$login->addValidator(new Zend_Validate_StringLength(3, 32));
		
		
		$password = new Zend_Form_Element_Password('password');
		$password->setLabel($translate->_('Password'));
		$password->setDecorators(array('ViewHelper', 'Label', 'Errors'));
		$password->setRequired(true);
		$password->addValidator('Alnum');
		$password->addValidator(new Zend_Validate_StringLength(3, 32));
		
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel($translate->_('Submit'));
		$submit->setDecorators(array('ViewHelper'));
		
		$this->addElements(array($login, $password, $submit));
	}
	
	
}