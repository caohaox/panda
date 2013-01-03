<?php 
class Application_Form_User_Edit extends Zend_Form {
	
	public function init() {
		$this->setDisableLoadDefaultDecorators(true);
 
        $this->addDecorator('FormElements')
        	 ->addDecorator('Form');
        	 
		$view = $this->getView();
		
		$this->setName('edit');
		$this->setAction($view->baseUrl('users/edit'));
		$this->setMethod('post');	
		
		$translate = Zend_Registry::get('translate');
		
		$name = new Zend_Form_Element_Text('name');
		$name->setLabel($translate->_('Name'));
		$name->setRequired(true);
		$name->addValidator(new Zend_Validate_StringLength(2, 64));
		$name->addFilter(new Zend_Filter_Alpha(array('allowwhitespace' => true)));
		$name->addFilter('StringTrim');
		$name->setDecorators(array('ViewHelper', new Admin_Form_Decorator_Text()));
		
		$lastname = new Zend_Form_Element_Text('lastname');
		$lastname->setLabel($translate->_('Lastname'));
		$lastname->setRequired(true);
		$lastname->addValidator(new Zend_Validate_StringLength(2, 64));
		$lastname->addFilter(new Zend_Filter_Alpha(array('allowwhitespace' => true)));
		$lastname->addFilter('StringTrim');
		$lastname->setDecorators(array('ViewHelper', new Admin_Form_Decorator_Text()));
		
		$name_eng = new Zend_Form_Element_Text('name_eng');
		$name_eng->setLabel($translate->_('Name (eng)'));
		$name_eng->setRequired(true);
		$name_eng->addValidator(new Zend_Validate_StringLength(2, 64));
		$name_eng->addFilter(new Zend_Filter_Alpha(array('allowwhitespace' => true)));
		$name_eng->addFilter('StringTrim');
		$name_eng->setDecorators(array('ViewHelper', new Admin_Form_Decorator_Text()));
		
		$lastname_eng = new Zend_Form_Element_Text('lastname_eng');
		$lastname_eng->setLabel($translate->_('Lastname (eng)'));
		$lastname_eng->setRequired(true);
		$lastname_eng->addValidator(new Zend_Validate_StringLength(2, 64));
		$lastname_eng->addFilter(new Zend_Filter_Alpha(array('allowwhitespace' => true)));
		$lastname_eng->addFilter('StringTrim');
		$lastname_eng->setDecorators(array('ViewHelper', new Admin_Form_Decorator_Text()));
		
		
		$status = new Zend_Form_Element_Select('status');
		$status->setLabel($translate->_('Active'));
		$status->setRequired(true);
		$status->addMultiOption('0', $translate->_('No'));
		$status->addMultiOption('1', $translate->_('Yes'));
		$status->setDecorators(array('ViewHelper', new Admin_Form_Decorator_Text()));
		
		$block = new Zend_Form_Element_Select('block');
		$block->setLabel($translate->_('Block'));
		$block->setRequired(true);
		$block->addMultiOption('0', $translate->_('No'));
		$block->addMultiOption('1', $translate->_('Yes'));
		$block->setDecorators(array('ViewHelper', new Admin_Form_Decorator_Text()));
		
		
		$user_id = new Zend_Form_Element_Hidden('user_id');
		$user_id->addFilter('digits');
		
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setName($translate->_('Save'));
		$submit->setDecorators(array('ViewHelper', new Admin_Form_Decorator_Submit()));
		$this->addElements(array($name, $lastname, $name_eng, $lastname_eng, $status, $block, $user_id, $submit));
	}
}