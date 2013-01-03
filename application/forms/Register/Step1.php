<?php 
class Application_Form_Register_Step1 extends Zend_Form {
	
	public function __construct() {
		
		$translate = Zend_Registry::get('translate');
		$request = Zend_Controller_Front::getInstance()->getRequest();
 		
		$this->setDisableLoadDefaultDecorators(true);
		
 
        $this->addDecorator('FormElements')
        	 ->addDecorator('Form');
        	 
		$view = $this->getView();
		
		$this->setAction($view->baseUrl('steps/step1'));
		$this->setMethod('post');
		
		$this->setName('step1_form');
		
		$username = new Zend_Form_Element_Text('name');
		$username->setDecorators(array('ViewHelper', new Site_Form_Decorator_DoubleText()));
		$username->setLabel($translate->_('Lastname_Name_Native'));
		$username->setRequired(true);
		$username->addFilter(new Zend_Filter_Alpha(array('allowwhitespace' => true)));
		$username->addValidator(new Zend_Validate_StringLength(2, 64));
		$username->addFilter('StringTrim');
		
		$lastname = new Zend_Form_Element_Text('lastname');
		$lastname->setDecorators(array('ViewHelper', new Site_Form_Decorator_DoubleText2()));
		$lastname->setRequired(true);
		$lastname->addFilter(new Zend_Filter_Alpha(array('allowwhitespace' => true)));
		$lastname->addValidator(new Zend_Validate_StringLength(2, 64));
		$lastname->addFilter('StringTrim');
		
		$username_eng = new Zend_Form_Element_Text('name_eng');
		$username_eng->setDecorators(array('ViewHelper', new Site_Form_Decorator_DoubleText()));
		$username_eng->setLabel($translate->_('Lastname_Name_Eng'));
		$username_eng->setRequired(true);
		$username_eng->addFilter(new Zend_Filter_Alpha(array('allowwhitespace' => true)));
		$username_eng->addValidator(new Zend_Validate_StringLength(2, 64));
		$username_eng->addFilter('StringTrim');
		
		$lastname_eng = new Zend_Form_Element_Text('lastname_eng');
		$lastname_eng->setDecorators(array('ViewHelper', new Site_Form_Decorator_DoubleText2()));
		$lastname_eng->setRequired(true);
		$lastname_eng->addFilter(new Zend_Filter_Alpha(array('allowwhitespace' => true)));
		$lastname_eng->addValidator(new Zend_Validate_StringLength(2, 64));
		$lastname_eng->addFilter('StringTrim');

		/*$email = new Zend_Form_Element_Text('email');
		$email->setDecorators(array('ViewHelper', new Site_Form_Decorator_Text()));
		$email->setRequired(true);
		$email->setLabel($translate->_('Email'));
		$email->addValidator('EmailAddress');
		$email->addValidator(new Site_Form_Validate_Email());
		$email->addValidator(new Zend_Validate_StringLength(3, 64));
		$email->addFilter('StringTrim');*/
		
		$telephone = new Zend_Form_Element_Text('telephone');
		$telephone->setDecorators(array('ViewHelper', new Site_Form_Decorator_Text()));
		$telephone->setRequired(true);
		$telephone->setLabel($translate->_('Telephone'));
		$telephone->addFilter('StripTags');
		$telephone->addValidator(new Zend_Validate_StringLength(3, 32));
		$telephone->addFilter('StringTrim');

		$skype = new Zend_Form_Element_Text('skype');
		$skype->setDecorators(array('ViewHelper', new Site_Form_Decorator_Text()));
		$skype->setRequired(true);
		$skype->setLabel($translate->_('Skype'));
		$skype->addFilter('StripTags');
		$skype->addValidator(new Zend_Validate_StringLength(3, 32));
		$skype->addFilter('StringTrim');
		
		$day = new Zend_Form_Element_Select('day');
		$day->setLabel($translate->_('Day / Month / Year'));
		$day->setRequired(true);
		$day->addValidator(new Zend_Validate_GreaterThan(0));
		$day->setDecorators(array('ViewHelper', new Site_Form_Decorator_Date_Day()));
		$day->addMultiOption(0, '');
		for($i = 1; $i <= 31; $i++) {
			$day->addMultiOption($i, $i);
		}
		
		$month = new Zend_Form_Element_Select('month');
		$month->addFilter('Digits');
		$month->setRequired(true);
		$month->addValidator(new Zend_Validate_GreaterThan(0));
		$month->setDecorators(array('ViewHelper', new Site_Form_Decorator_Date_Month()));
		$month->addMultiOption(0, '');
		
		$month->addMultiOption(1, $translate->_('January'));
		$month->addMultiOption(2, $translate->_('February'));
		$month->addMultiOption(3, $translate->_('Marth'));
		$month->addMultiOption(4, $translate->_('April'));
		$month->addMultiOption(5, $translate->_('May'));
		$month->addMultiOption(6, $translate->_('June'));
		$month->addMultiOption(7, $translate->_('July'));
		$month->addMultiOption(8, $translate->_('August'));
		$month->addMultiOption(9, $translate->_('September'));
		$month->addMultiOption(10, $translate->_('October'));
		$month->addMultiOption(11, $translate->_('November'));
		$month->addMultiOption(12, $translate->_('December'));
		
		
		$year = new Zend_Form_Element_Select('year');
		$year->addFilter('Digits');
		$year->setDecorators(array('ViewHelper', new Site_Form_Decorator_Date_Year()));
		$year->setRequired(true);
		$year->addValidator(new Zend_Validate_GreaterThan(0));
		$year->addMultiOption(0, '');
		for($i = 1994; $i >= 1940; $i--) {
			$year->addMultiOption($i, $i);
		}
		
		$date_error = new Zend_Form_Element_Hidden('date_error');
		$date_error->setDecorators(array('ViewHelper', 'Errors', new Site_Form_Decorator_Date_Error()));
		
		
		/*$city = new Zend_Form_Element_Text('city');
		$city->setDecorators(array('ViewHelper', new Site_Form_Decorator_FieldsetText()));
		$city->setLabel($translate->_('City (Eng)'));
		$city->addFilter(new Zend_Filter_Alnum(array('allowwhitespace' => true)));
		$city->addValidator(new Zend_Validate_StringLength(3, 64));
		$city->addFilter('StringTrim');*/
		
		$height = new Zend_Form_Element_Text('height');
		$height->setDecorators(array('ViewHelper', new Site_Form_Decorator_Text()));
		$height->setLabel($translate->_('Height (cm)'));
		$height->addFilter(new Zend_Filter_Digits());
		
		/*$weight = new Zend_Form_Element_Text('weight');
		$weight->setDecorators(array('ViewHelper', new Site_Form_Decorator_FieldsetText()));
		$weight->setLabel($translate->_('Weight (kg)'));
		$weight->addFilter(new Zend_Filter_Digits());*/
		
		
		$this->addElements(array($username, $lastname, $username_eng, $lastname_eng, $telephone, $skype, $day, $month, $year, $date_error));
		
		$this->addDisplayGroup(array('name', 'lastname', 'name_eng', 'lastname_eng'), 'group1', array('legend' => $translate->_('Name')));
		$this->addDisplayGroup(array('telephone', 'skype'), 'group2', array('legend' => $translate->_('Contact info')));
		$this->addDisplayGroup(array('day', 'month', 'year', 'date_error'), 'group3', array('legend' => $translate->_('Birth date')));
		
		$this->addElements(array($height));
		//$city, , $weight
		
		$db = new Application_Model_Profileoption();
		if($view->user->is_man) {
			$options = $db->getOptions(array('section' => 'step1', 'for_man' => 1));
		}else {
			$options = $db->getOptions(array('section' => 'step1', 'for_woman' => 1));
		}
		
		$addLater = array();
		$addBeforeChildren = array();
		$addNow = array();
		
		foreach($options as $option) {
			if($option['type'] == 'select') {
				$newSelect = new Zend_Form_Element_Select('profile_select' . $option['profile_option_id']);
				
				
				$newSelect->setLabel($option['name']);
				
				$values = $db->getValues($option['profile_option_id']);
				
				$newSelect->addMultiOption(0, $translate->_('Not selected'));
				foreach($values as $value) {
					$newSelect->addMultiOption($value['profile_option_value_id'], $value['name']);
				}
				
				if(in_array($option['profile_option_id'], array(
					Zend_registry::get('body'),
					Zend_registry::get('eyes'),
					Zend_registry::get('hair'),
					Zend_registry::get('smoking'),
					Zend_registry::get('alcohol'),
					Zend_registry::get('education'),
					Zend_registry::get('speciality'),
				))) {
					$newSelect->setDecorators(array('ViewHelper', new Site_Form_Decorator_Text()));
					
					$this->addElements(array($newSelect));
					
				}elseif(in_array($option['profile_option_id'], array(
					Zend_registry::get('region_man'),
					Zend_registry::get('region_woman'),
				))) {
					$newSelect->setDecorators(array('ViewHelper', new Site_Form_Decorator_FieldsetText()));
					$addNow[] = $newSelect;
				}elseif(in_array($option['profile_option_id'], array(
					Zend_registry::get('sfera'),
				))) {
					$newSelect->setDecorators(array('ViewHelper', new Site_Form_Decorator_FieldsetText()));
					$addBeforeChildren[] = $newSelect;
				}else {
					$newSelect->setDecorators(array('ViewHelper', new Site_Form_Decorator_FieldsetText()));
					
					$addLater[] = $newSelect;
				}
			}elseif($option['type'] == 'checkbox') {
				$newCheckbox = new Zend_Form_Element_MultiCheckbox('profile_checkbox' . $option['profile_option_id']);
				$newCheckbox->setDecorators(array('ViewHelper', new Site_Form_Decorator_Checkbox()));
				$newCheckbox->setLabel($option['name']);
				
				$values = $db->getValues($option['profile_option_id']);
				
				foreach($values as $value) {
					$newCheckbox->addMultiOption($value['profile_option_value_id'], $value['name']);
				}
				
				$addLater[] = $newCheckbox;
			}
		}
		
		//блок персональные данные
		$this->addDisplayGroup(array('height', 'profile_select' . Zend_registry::get('body'), 'profile_select' . Zend_registry::get('eyes'), 'profile_select' . Zend_registry::get('hair')), 'group5', array('legend' => $translate->_('Personal information')));
		
		$this->addElements($addNow);
		
		//блок курение
		$this->addDisplayGroup(array('profile_select' . Zend_registry::get('smoking'), 'profile_select' . Zend_registry::get('alcohol')), 'group6', array('class' => 'fieldSetWithoutLegend'));
		
		//блок образование
		$this->addDisplayGroup(array('profile_select' . Zend_registry::get('education'), 'profile_select' . Zend_registry::get('speciality')), 'group7', array('class' => 'fieldSetWithoutLegend'));
		
		$this->addElements($addLater);
		
		$languages = new Zend_Form_SubForm('language');
		$languages->setDecorators(array(new Site_Form_Decorator_Languages()));
		
		$db_user = new Application_Model_User();
		$languageCountByUser = count($db_user->getLanguagesByUserId($view->user->user_id));
		
		if($request->getParam('languages')) {
			$languageCount = count($request->getParam('languages')) / 2;
		}elseif($languageCountByUser) {
			$languageCount = $languageCountByUser;
		}else $languageCount = 1;
		//var_dump($languageCount);
		$db_language = new Application_Model_Language();
		$all_languages = $db_language->getLanguages();
		$skills = $db_language->getSkills();
		
		for($c = 0; $c < $languageCount; $c++) {
			$language = new Zend_Form_Element_Select('language' . $c, array('belongsTo' => 'languages'));
			$language->setDecorators(array('ViewHelper'));
			$language->setLabel($translate->_('Language / skill'));
			
			$language->addMultiOption(0, $translate->_('Not selected'));
			foreach($all_languages as $lang) {
				$language->addMultiOption($lang['all_language_id'], $lang['name']);
			}
			
			$languages->addElement($language);
			
			$skill = new Zend_Form_Element_Select('skill' . $c, array('belongsTo' => 'languages'));
			$skill->setDecorators(array('ViewHelper'));
			
			$skill->addMultiOption(0, $translate->_('Not selected'));
			foreach($skills as $sk) {
				$skill->addMultiOption($sk['skill_id'], $sk['name']);
			}
			
			$languages->addElement($skill);
		}
		
		$this->addSubForm($languages, 'languages');
		
		$this->addElements($addBeforeChildren);
		
		//дети
		$childrenCountByUser = count($db_user->getChildrenByUserId($view->user->user_id));
		
		if($request->getParam('children')) {
			$childrenCount = count($request->getParam('children'));
		}elseif($childrenCountByUser) {
			$childrenCount = $childrenCountByUser;
		}else $childrenCount = 1;
		
		$children = new Zend_Form_SubForm('children');
		$children->setDecorators(array(new Site_Form_Decorator_Children()));
		
		$childs = new Zend_Form_Element_Select('childs', array('belongsTo' => 'children'));
		$childs->setDecorators(array('ViewHelper'));
		$childs->setLabel($translate->_('Children'));
		$childs->addMultiOption(0, $translate->_('No'));
		$childs->addMultiOption(1, $translate->_('Yes'));
		$children->addElement($childs);
			
		
		for($c = 0; $c < $childrenCount; $c++) {
			$child = new Zend_Form_SubForm('child' . $c);
			$child->setDecorators(array(new Site_Form_Decorator_Child()));
			
			$child_name = new Zend_Form_Element_Text('name', array('belongsTo' => 'children[child' . $c . ']'));
			$child_name->setDecorators(array('ViewHelper'));
			$child_name->setLabel($translate->_('Child'));
			$child->addElement($child_name);
			
			
			$child_age = new Zend_Form_Element_Text('child_age', array('belongsTo' => 'children[child' . $c . ']'));
			$child_age->setDecorators(array('ViewHelper'));
			$child_age->setLabel($translate->_('Age'));
			$child->addElement($child_age);
			
			$child_together = new Zend_Form_Element_Select('together', array('belongsTo' => 'children[child' . $c . ']'));
			$child_together->setDecorators(array('ViewHelper'));
			$child_together->setLabel($translate->_('Live together'));
			$child_together->addMultiOption(0, $translate->_('No'));
			$child_together->addMultiOption(1, $translate->_('Yes'));
			$child->addElement($child_together);
			
			$children->addSubForm($child, 'child' . $c);
		}
		
		$this->addSubForm($children, 'children');
		
		$image = new Zend_Form_Element_File('image');
		$image->setLabel($translate->_('Photo 1 (main)'));
		$image->addValidator('Extension', false, 'jpg,png,gif');
		$image->setDestination(realpath(APPLICATION_PATH . '/../public/img/data/users'));
		$image->addValidator('Size', false, 2048000);
		$image->addValidator('Count', false, 1);
		$image->setDecorators(array('File', new Site_Form_Decorator_Step1Image()));
		
		$image2 = new Zend_Form_Element_File('image2');
		$image2->setLabel($translate->_('Photo 2'));
		$image2->addValidator('Extension', false, 'jpg,png,gif');
		$image2->setDestination(realpath(APPLICATION_PATH . '/../public/img/data/users'));
		$image2->addValidator('Size', false, 2048000);
		$image2->addValidator('Count', false, 1);
		$image2->setDecorators(array('File', new Site_Form_Decorator_Step1Image()));
		
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel($translate->_('Continue'));
		$submit->setDecorators(array('ViewHelper', new Site_Form_Decorator_Submit()));
		
		$this->addElements(array($image, $image2, $submit));
		
		$this->addDisplayGroup(array('image', 'image2', 'submit'), 'group4', array('legend' => $translate->_('Upload profile image')));
		
		$this->setDisplayGroupDecorators(array(
			'FormElements',
			'Fieldset'
		));
		
		//$this->getDisplayGroup('group4')->setDecorators(array(new Site_Form_Decorator_Languages(), 'FormElements'));
	}
	
	public function isValid($data) {
		if($this->image->getFileName()) {
			$oldName = pathinfo($this->image->getFileName());
			$newName = str_ireplace(array('.', ' '), '', microtime()) . '.' . $oldName['extension'];
			$this->image->addFilter('Rename', $newName);
		}
		return parent::isValid($data);
	}
}