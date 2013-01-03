<?php 
class Application_Form_Profile_Settings extends Zend_Form {
	
	public function __construct() {
		
		$translate = Zend_Registry::get('translate');
		$request = Zend_Controller_Front::getInstance()->getRequest();
 		
		$this->setDisableLoadDefaultDecorators(true);
		
 
        $this->addDecorator('FormElements')
        	 ->addDecorator('Form');
        	 
		$view = $this->getView();
		
		$this->setAction($view->baseUrl('profile/settings'));
		$this->setMethod('post');
		
		$this->setName('settings_form');
		
		

		$email = new Zend_Form_Element_Text('email');
		$email->setDecorators(array('ViewHelper', new Site_Form_Decorator_Profile_SettingsText()));
		$email->setRequired(true);
		$email->setLabel($translate->_('Email'));
		$email->addValidator('EmailAddress');
		$email->addValidator(new Site_Form_Validate_Email());
		$email->addValidator(new Zend_Validate_StringLength(3, 64));
		$email->addFilter('StringTrim');
		
		$telephone = new Zend_Form_Element_Text('telephone');
		$telephone->setDecorators(array('ViewHelper', new Site_Form_Decorator_Profile_SettingsText()));
		$telephone->setRequired(true);
		$telephone->setLabel($translate->_('Telephone'));
		$telephone->addFilter('StripTags');
		$telephone->addValidator(new Zend_Validate_StringLength(3, 32));
		$telephone->addFilter('StringTrim');

		$skype = new Zend_Form_Element_Text('skype');
		$skype->setDecorators(array('ViewHelper', new Site_Form_Decorator_Profile_SettingsText()));
		$skype->setRequired(true);
		$skype->setLabel($translate->_('Skype'));
		$skype->addFilter('StripTags');
		$skype->addValidator(new Zend_Validate_StringLength(3, 32));
		$skype->addFilter('StringTrim');
		
		$this->addElements(array($email,$telephone,$skype));
		$this->addDisplayGroup(array('email', 'telephone', 'skype'), 'group1', array('legend' => $translate->_('Contact information')));
		
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel($translate->_('Save'));
		$submit->setDecorators(array('ViewHelper', new Site_Form_Decorator_Profile_Submit2()));
		$submit->setAttrib('form_name', 'settings_form');
		
		$notification = new Zend_Form_Element_Checkbox('notification');
		$notification->setDecorators(array('ViewHelper', new Site_Form_Decorator_Profile_TextUniform()));
		$notification->addFilter(new Zend_Filter_Digits());
		
		$this->addElements(array($notification));
		$this->addDisplayGroup(array('notification'), 'group3', array('legend' => $translate->_('Would you like to receive notifications by email') . '?'));
		
		$notification_in_days = new Zend_Form_Element_Select('notification_in_days');
		$notification_in_days->setDecorators(array('ViewHelper', new Site_Form_Decorator_Profile_TextUniform()));
		$notification_in_days->addFilter(new Zend_Filter_Digits());
		
		$notification_in_days->addMultiOption(0, $translate->_('Not selected'));
		$notification_in_days->addMultiOption(99, $translate->_('Several times a day'));
		$notification_in_days->addMultiOption(1, $translate->_('Every day'));
		$notification_in_days->addMultiOption(3, $translate->_('Every 3 days'));
		$notification_in_days->addMultiOption(7, $translate->_('Every week'));
		
		$this->addElements(array($notification_in_days));
		$this->addDisplayGroup(array('notification_in_days'), 'group4', array('legend' => $translate->_('How often you would like to receive notifications') . '?'));
		
		$home = new Zend_Form_Element_Checkbox('home');
		$home->setDecorators(array('ViewHelper', new Site_Form_Decorator_Profile_TextUniform()));
		$home->addFilter(new Zend_Filter_Digits());
		
		$this->addElements(array($home));
		$this->addDisplayGroup(array('home'), 'group2', array('legend' => $translate->_('Allow image on the main page')));
		
		$this->setDisplayGroupDecorators(array(
			'FormElements',
			'Fieldset'
		));
		
		$this->addElements(array($submit));
		
	}
}