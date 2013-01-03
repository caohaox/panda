<?php 
class Application_Form_Spam extends Zend_Form {
	
	public function __construct() {
		$translate = Zend_Registry::get('translate');
		$this->setDisableLoadDefaultDecorators(true);
 
        $this->addDecorator('FormElements')
        	 ->addDecorator('Form');
        	 
		$view = $this->getView();
		$translate = Zend_Registry::get('translate');
		
		$this->setName('spam_form');
		$this->setMethod('post');
		$this->setAttrib('class', 'spam_form');
	
		$type = new Zend_Form_Element_Radio('type');
		$type->setDecorators(array('ViewHelper', new Site_Form_Decorator_Text()));
		$type->setRequired(true);
		$type->addMultiOption(1, $translate->_('Abuse'));
		$type->addMultiOption(2, $translate->_('Spam'));
		$type->setValue(1);
		
		$text = new Zend_Form_Element_Textarea('text');
		$text->setDecorators(array('ViewHelper', new Site_Form_Decorator_Text()));
		$text->setRequired(true);
		$text->addValidator('Alnum');
		$text->addValidator(new Zend_Validate_StringLength(3));
		$text->addFilter('StringTrim');
		$text->addFilter('StripTags');
		
		
		$message_id = new Zend_Form_Element_Hidden('message_id');

		
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setDecorators(array(new Site_Form_Decorator_SpamSubmit()));
		$submit->setLabel($translate->_('Submit'));
		$submit->setAttrib('form_name', 'spam_form');
		$submit->setAttrib('action', $view->baseUrl('chat/spam'));
		
		$this->addElements(array($type, $text, $message_id, $submit));
	}
}