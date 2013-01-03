<?php 
class Application_Form_Register_RegisterGentlemen extends Zend_Form {
	
	public function __construct() {
		
		$translate = Zend_Registry::get('translate');
		
		$this->setDisableLoadDefaultDecorators(true);
 
        $this->addDecorator('FormElements')
        	 ->addDecorator('Form');
        	 
		$view = $this->getView();
		
		$this->setName('register_form');
	

		/*$username = new Zend_Form_Element_Text('name');
		$username->setDecorators(array('ViewHelper', new Site_Form_Decorator_Text()));
		$username->setLabel($translate->_('Name'));
		$username->setRequired(true);
		$username->addFilter(new Zend_Filter_Alpha(array('allowwhitespace' => true)));
		$username->addValidator(new Zend_Validate_StringLength(3, 64));
		$username->addFilter('StringTrim');*/
		
		$email = new Zend_Form_Element_Text('email');
		$email->setRequired(true);
		$email->setDecorators(array('ViewHelper', new Site_Form_Decorator_Text()));
		$email->setLabel($translate->_('Email'));
		$email->addValidator('EmailAddress');
		$email->addValidator(new Site_Form_Validate_Email());
		$email->addValidator(new Zend_Validate_StringLength(3, 64));
		$email->addFilter('StringTrim');
		
		$password = new Zend_Form_Element_Password('password');
		$password->setRequired(true);
		$password->setDecorators(array('ViewHelper', new Site_Form_Decorator_Text()));
		$password->setLabel($translate->_('Password'));
		$password->addValidator('Alnum');
		$password->addValidator(new Zend_Validate_StringLength(3, 32));
		$password->addFilter('StringTrim');
		
		$password_confirm = new Zend_Form_Element_Password('password_confirm');
		$password_confirm->setRequired(true);
		$password_confirm->setDecorators(array('ViewHelper', new Site_Form_Decorator_Text()));
		$password_confirm->setLabel($translate->_('Confirm password'));
		$password_confirm->addValidator('Alnum');
		$password_confirm->addValidator(new Zend_Validate_StringLength(3, 32));
		$identicalValidator = new Zend_Validate_Identical('password');
		$password_confirm->addValidator($identicalValidator->setMessage($translate->_('Passwords do not match')));
		$password_confirm->addFilter('StringTrim');
		
		$gentle = new Zend_Form_Element_Hidden('gentle');
		$gentle->setDecorators(array('ViewHelper', new Site_Form_Decorator_Gentle()));
		
		$view = Zend_Registry::get('view');
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
		$captcha->setRequired(true);
		$captcha->setLabel($translate->_('Enter text'));
		$captcha->setDecorators(array(new Site_Form_Decorator_Captcha()));
		$captcha->setAttrib('reload_action', $view->baseUrl('register/reloadmen'));
		

		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel($translate->_('Submit'));
		$submit->setDecorators(array('ViewHelper', new Site_Form_Decorator_AjaxSubmit()));
		$submit->setAttrib('form_name', 'register_form');
		$submit->setAttrib('action', $view->baseUrl('register/registergentlemen'));
		
		$this->addElements(array($email, $password, $password_confirm, $gentle, $captcha, $submit));
	}
}