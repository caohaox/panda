<?php 
class Application_Form_Mail extends Zend_Form {
	
	public function init() {
		$this->setDisableLoadDefaultDecorators(true);
 
        $this->addDecorator('FormElements')
        	 ->addDecorator('Form');
        	 
		$view = $this->getView();
		$translate = Zend_Registry::get('translate');
		
		$this->setName('mail_form');
		$this->setAttrib('class', 'mail_form');
		$this->setMethod('post');
		$this->setAction($view->baseUrl('mail/send'));
	
		$email = new Zend_Form_Element_Text('email');
		$email->setLabel($translate->_('Email'));
		$email->setDecorators(array('ViewHelper', 'Label', 'Errors'));
		$email->setRequired(true);
		$email->addValidator('EmailAddress');
		
		$title = new Zend_Form_Element_Text('title');
		$title->setLabel($translate->_('Title'));
		$title->setDecorators(array('ViewHelper', 'Label', 'Errors'));
		$title->setRequired(true);
		$title->addFilter('StripTags');
		
		$text = new Zend_Form_Element_Textarea('text');
		$text->setLabel($translate->_('Text'));
		$text->setDecorators(array('ViewHelper', 'Label', 'Errors'));
		$text->setRequired(true);
		$text->setAttrib('class', 'use_redactor');
		
		$redirect = new Zend_Form_Element_Hidden('redirect');

		
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel($translate->_('Submit'));
		$submit->setDecorators(array('ViewHelper'));
		$submit->setAttrib('class', 'button');
		
		$this->addElements(array($email, $title, $text, $redirect, $submit));
	}
	
	
}