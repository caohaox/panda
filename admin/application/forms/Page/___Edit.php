<?php 
class Application_Form_Page_Edit extends Zend_Form {
	
	public function init() {
		$this->setDisableLoadDefaultDecorators(true);
 
        $this->addDecorator('FormElements')
        	 ->addDecorator('Form');
        	 
		$view = $this->getView();
		
		$this->setName('edit');
		$this->setAction($view->baseUrl('page/edit'));
		$this->setMethod('post');
			
		$seo_url = new Zend_Form_Element_Text('seo_url');
		$seo_url->setLabel('СЕО адрес');
		$seo_url->setRequired(true);
		$seo_url->addFilter(new Zend_Filter_StringTrim());
		$seo_url->addValidator(new Zend_Validate_StringLength(0, 128));
		$seo_url->addValidator(new Admin_Validate_EnglishAlnum());
		$seo_url->setDecorators(array('ViewHelper', new Admin_Form_Decorator_Text()));
		
		$template = new Zend_Form_Element_Select('template_id');
		$template->setLabel('Шаблон');
		$template->setRequired(true);
		$template->setDecorators(array('ViewHelper', new Admin_Form_Decorator_Text()));
		
		$db = new Application_Model_Template();
		$templates = $db->getTemplates();
		
		foreach($templates as $entity) {
			$template->addMultiOption($entity['template_id'], $entity['label']);
		}
		
		$active = new Zend_Form_Element_Checkbox('status');
		$active->setLabel('Активна?');
		$active->setDecorators(array('ViewHelper', new Admin_Form_Decorator_Checkbox()));
		
		$page_id = new Zend_Form_Element_Hidden('page_id');
		$page_id->addFilter('digits');
		
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
		$languageForm = new Zend_Form_SubForm('language');
		$languageForm->setDecorators(array('FormElements'));
		
		foreach($languages as $language) {
			$newSubForm = new Zend_Form_SubForm($language['language_id']);
			$newSubForm->setDecorators(array('FormElements', new Admin_Form_Decorator_Subform()));
			$newSubForm->setLegend($language['name']);
			
			$title = new Zend_Form_Element_Text('title');
			$title->setLabel('title');
			$title->setRequired(true);
			$title->addValidator(new Zend_Validate_StringLength(0, 255));
			$title->setDecorators(array('ViewHelper', new Admin_Form_Decorator_Text()));
			$title->addFilter('StripTags');
			$title->addFilter('StringTrim');
			
			$meta_keywords = new Zend_Form_Element_Text('meta_keywords');
			$meta_keywords->setLabel('meta_keywords');
			$meta_keywords->addValidator(new Zend_Validate_StringLength(0, 255));
			$meta_keywords->setDecorators(array('ViewHelper', new Admin_Form_Decorator_Text()));
			$meta_keywords->addFilter('StripTags');
			$meta_keywords->addFilter('StringTrim');
			
			$meta_description = new Zend_Form_Element_Text('meta_description');
			$meta_description->setLabel('meta_description');
			$meta_description->addValidator(new Zend_Validate_StringLength(0, 255));
			$meta_description->setDecorators(array('ViewHelper', new Admin_Form_Decorator_Text()));
			$meta_description->addFilter('StripTags');
			$meta_description->addFilter('StringTrim');
			
			$description = new Zend_Form_Element_Textarea('description');
			$description->setLabel('Текст страницы');
			$description->setRequired(true);
			$description->setDecorators(array('ViewHelper', new Admin_Form_Decorator_Textarea()));
			
			$newSubForm->addElements(array($title, $meta_description, $meta_keywords, $description));
			
			$languageForm->addSubForm($newSubForm, $language['language_id']);
		}
		
		$this->addSubForm($languageForm, 'language');

		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setName('Сохранить');
		$submit->setDecorators(array('ViewHelper', new Admin_Form_Decorator_Submit()));
		
		
		$this->addElements(array($seo_url, $template, $active, $page_id, $submit));
	}
}