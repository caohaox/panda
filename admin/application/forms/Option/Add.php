<?php 
class Application_Form_Option_Add extends Zend_Form {
	
	public function init() {
		$this->setDisableLoadDefaultDecorators(true);
 
        $this->addDecorator('FormElements')
        	 ->addDecorator('Form');
        	 
		$view = $this->getView();
		
		$this->setName('edit');
		$this->setAction($view->baseUrl('options/add'));
		$this->setMethod('post');
		$translate = Zend_Registry::get('translate');	
		
		$section = new Zend_Form_Element_Select('section');
		$section->setLabel($translate->_('Section'));
		$section->setRequired(true);
		$section->setDecorators(array('ViewHelper', new Admin_Form_Decorator_Text()));
		$section->addMultiOption('step1', 'step1');
		$section->addMultiOption('step2', 'step2');
		$section->addMultiOption('step3', 'step3');
		
		$type = new Zend_Form_Element_Select('type');
		$type->setLabel($translate->_('Type'));
		$type->setRequired(true);
		$type->setDecorators(array('ViewHelper', new Admin_Form_Decorator_Text()));
		$type->addMultiOption('select', 'select');
		$type->addMultiOption('checkbox', 'checkbox');
		
		$for_man = new Zend_Form_Element_Select('for_man');
		$for_man->setLabel($translate->_('For men'));
		$for_man->setDecorators(array('ViewHelper', new Admin_Form_Decorator_Text()));
		$for_man->addMultiOption('1', $translate->_('Yes'));
		$for_man->addMultiOption('0', $translate->_('No'));
		
		
		$for_woman = new Zend_Form_Element_Select('for_woman');
		$for_woman->setLabel($translate->_('For women'));
		$for_woman->setDecorators(array('ViewHelper', new Admin_Form_Decorator_Text()));
		$for_woman->addMultiOption('1', $translate->_('Yes'));
		$for_woman->addMultiOption('0', $translate->_('No'));
		
		
		$sort_order = new Zend_Form_Element_Text('sort_order');
		$sort_order->setLabel($translate->_('Sort order'));
		$sort_order->addFilter('Digits');
		$sort_order->setDecorators(array('ViewHelper', new Admin_Form_Decorator_Text()));

			
			
		$db_languages = new Application_Model_Language();
		$languages = $db_languages->getLanguages();
		
		
		//tabs
		$languageTabs = new Zend_Form_SubForm('tabs');
		$languageTabs->setDecorators(array(new Admin_Form_Decorator_Tab()));
		foreach($languages as $language) {
			$newTab = new Zend_Form_Element_Text('language' . $language['language_id']);
			$newTab->setDecorators(array());
			$newTab->setAttrib('language_name', $language['name']);
			$languageTabs->addElements(array($newTab));
		}
		$this->addSubForm($languageTabs, 'tabs');
		
		//languages
		$languageForm = new Zend_Form_SubForm('description');
		$languageForm->setDecorators(array('FormElements'));
		
		foreach($languages as $language) {
			$newSubForm = new Zend_Form_SubForm($language['language_id']);
			$newSubForm->setDecorators(array('FormElements', new Admin_Form_Decorator_Subform()));
			$newSubForm->setLegend($language['name']);
			
			$name = new Zend_Form_Element_Text('name');
			$name->setLabel($translate->_('Name'));
			$name->setRequired(true);
			$name->addValidator(new Zend_Validate_StringLength(0, 255));
			$name->setDecorators(array('ViewHelper', new Admin_Form_Decorator_Text()));
			$name->addFilter('StripTags');
			$name->addFilter('StringTrim');
			
			
			$newSubForm->addElements(array($name));
			
			$languageForm->addSubForm($newSubForm, $language['language_id']);
		}
		
		$this->addSubForm($languageForm, 'language');

		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setName($translate->_('Save'));
		$submit->setDecorators(array('ViewHelper', new Admin_Form_Decorator_Submit()));
		
		
		$this->addElements(array($section, $type, $for_man, $for_woman, $sort_order, $submit));
	}
}