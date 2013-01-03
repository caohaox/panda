<?php 
class Application_Form_Login extends Zend_Form {
	
	public function __construct() {
		$translate = Zend_Registry::get('translate');
		$this->setDisableLoadDefaultDecorators(true);
 
        $this->addDecorator('FormElements')
        	 ->addDecorator('Form');
        	 
		$view = $this->getView();
		
		$this->setName('login_form');
		$this->setAction($view->baseUrl('login/index'));
		$this->setMethod('post');
	
		$email = new Zend_Form_Element_Text('email');
		$email->setDecorators(array('ViewHelper', new Site_Form_Decorator_Text2()));
		$email->setLabel($translate->_('Email'));
		$email->setRequired(true);
		$email->addValidator('EmailAddress');
		$email->addFilter('StringTrim');
		
		$password = new Zend_Form_Element_Password('password');
		$password->setDecorators(array('ViewHelper', new Site_Form_Decorator_Text2()));
		$password->setLabel($translate->_('Password'));
		$password->setRequired(true);
		$password->addValidator('Alnum');
		$password->addValidator(new Zend_Validate_StringLength(3, 32));
		$password->addFilter('StringTrim');
		
		
		$forget = new Zend_Form_Element_Hidden('forget');
		$forget->setDecorators(array('ViewHelper', new Site_Form_Decorator_ForgetPassword()));
		$forget->setLabel($translate->_('I forgot my password'));
		
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setDecorators(array(new Site_Form_Decorator_AjaxSubmit()));
		$submit->setLabel($translate->_('Enter'));
		$submit->setAttrib('form_name', 'login_form');
		$submit->setAttrib('action', $view->baseUrl('login/index'));
		
		$this->addElements(array($email, $password, $forget, $submit));
	}
}