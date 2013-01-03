<?php 
class Application_Form_Register_Restore extends Zend_Form {
	
	public function __construct() {
		
		$this->setDisableLoadDefaultDecorators(true);
 
        $this->addDecorator('FormElements')
        	 ->addDecorator('Form');
        	 
		$view = $this->getView();
		
		$this->setName('restore');
		$translate = Zend_registry::get('translate');
		
		$password = new Zend_Form_Element_Password('password');
		$password->setDecorators(array('ViewHelper', new Site_Form_Decorator_Text()));
		$password->setLabel($translate->_('Password'));
		$password->setRequired(true);
		$password->addValidator('Alnum');
		$password->addValidator(new Zend_Validate_StringLength(3, 32));
		$password->addFilter('StringTrim');
		
		$password_confirm = new Zend_Form_Element_Password('password_confirm');
		$password_confirm->setDecorators(array('ViewHelper', new Site_Form_Decorator_Text()));
		$password_confirm->setLabel($translate->_('Confirm password'));
		$password_confirm->setRequired(true);
		$password_confirm->addValidator('Alnum');
		$password_confirm->addValidator(new Zend_Validate_StringLength(3, 32));
		$identicalValidator = new Zend_Validate_Identical('password');
		$password_confirm->addValidator($identicalValidator->setMessage($translate->_('Passwords do not match')));
		$password_confirm->addFilter('StringTrim');
		
		/*$view = Zend_Registry::get('view');
		$captcha = new Zend_Form_Element_Captcha('captcha', array(
			'captcha' 		=> 'Image',
			'captchaOptions'=> array(
						        'captcha' 	=> 'Image',
						        'wordLen' 	=> 6,
						        'timeout' 	=> 300,
						        'height' 	=> 50,
						        'messages' 	=> array(
				        						'badCaptcha' => $translate->_('Text is not valid')
				        						),
						        'font' 		=> APPLICATION_PATH . '/../public/tahoma.ttf',
						        'imgDir' 	=> APPLICATION_PATH . '/../public/img/captcha/',
						        'imgUrl' 	=> $view->baseUrl('/img/captcha/')		        
		    )));
		$captcha->setDecorators(array(new Site_Form_Decorator_Text()));
		$captcha->setLabel($translate->_('Enter text'));
		$captcha->setRequired(true);*/
		
		/*$code = new Zend_Form_Element_Hidden('code');
		$code->setDecorators(array('ViewHelper'));
		$code->setRequired(true);
		$code->addValidator('Digits');*/
		
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setDecorators(array('ViewHelper', new Site_Form_Decorator_Submit()));
		$submit->setLabel($translate->_('Next'));

		
		$this->addElements(array($password, $password_confirm, $submit));
	}
}