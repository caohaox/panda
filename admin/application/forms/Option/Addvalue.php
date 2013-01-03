<?php 
class Application_Form_Option_Addvalue extends Zend_Form {
	
	public function init() {
		$this->setDisableLoadDefaultDecorators(true);
 
        $this->addDecorator('FormElements')
        	 ->addDecorator('Form');
        	 
		$view = $this->getView();
		
		$this->setName('edit');
		$this->setAction($view->baseUrl('options/addvalue'));
		$this->setMethod('post');
		$translate = Zend_Registry::get('translate');
		
		$db_languages = new Application_Model_Language();
		$languages = $db_languages->getLanguages();
		
		$profile_option_id = new Zend_Form_Element_Hidden('profile_option_id');
		$profile_option_id->addFilter('digits');
		
		$sort_order = new Zend_Form_Element_Text('sort_order');
		$sort_order->setLabel($translate->_('Sort order'));
		$sort_order->addFilter('Digits');
		$sort_order->setDecorators(array('ViewHelper', new Admin_Form_Decorator_Text()));
		
		$related_option_value = new Zend_Form_Element_Hidden('related_option_value');
		$related_option_value->setLabel($translate->_('related_option_value'));
		$related_option_value->addFilter('Digits');
		$related_option_value->setDecorators(array('ViewHelper', new Admin_Form_Decorator_Relatedvalue()));
		
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
			//$name->addFilter('StripTags');
			$name->addFilter('StringTrim');
			
			
			$newSubForm->addElements(array($name));
			
			$languageForm->addSubForm($newSubForm, $language['language_id']);
		}
		
		$this->addSubForm($languageForm, 'language');

		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setName($translate->_('Save'));
		$submit->setDecorators(array('ViewHelper', new Admin_Form_Decorator_Submit()));
		
		
		$this->addElements(array($sort_order, $related_option_value, $profile_option_id, $submit));
	}
}