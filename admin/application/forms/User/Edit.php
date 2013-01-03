<?php 
class Application_Form_User_Edit extends Zend_Form {
	
	public function init() {
		
		$translate = Zend_Registry::get('translate');
		$request = Zend_Controller_Front::getInstance()->getRequest();
 		
		//$this->setDisableLoadDefaultDecorators(true);
		
 
        //$this->addDecorator('FormElements')
        	// ->addDecorator(new Site_Form_Decorator_Profile_Profile())
        	 //->addDecorator('Form');
        	 
		$view = $this->getView();
		
		$this->setAction($view->baseUrl('users/edit/user_id/' . $request->getParam('user_id')));
		$this->setMethod('post');
		
		$this->setName('steps_form');
		
		$username = new Zend_Form_Element_Text('name');
		
		$username->setLabel($translate->_('Lastname_Name_Native'));
		$username->setRequired(true);
		$username->addFilter(new Zend_Filter_Alpha(array('allowwhitespace' => true)));
		$username->addValidator(new Zend_Validate_StringLength(2, 64));
		$username->addFilter('StringTrim');
		
		$lastname = new Zend_Form_Element_Text('lastname');
		
		$lastname->setRequired(true);
		$lastname->addFilter(new Zend_Filter_Alpha(array('allowwhitespace' => true)));
		$lastname->addValidator(new Zend_Validate_StringLength(2, 64));
		$lastname->addFilter('StringTrim');
		
		$username_eng = new Zend_Form_Element_Text('name_eng');
		
		$username_eng->setLabel($translate->_('Lastname_Name_Eng'));
		$username_eng->setRequired(true);
		$username_eng->addFilter(new Zend_Filter_Alpha(array('allowwhitespace' => true)));
		$username_eng->addValidator(new Zend_Validate_StringLength(2, 64));
		$username_eng->addFilter('StringTrim');
		
		$lastname_eng = new Zend_Form_Element_Text('lastname_eng');
		
		$lastname_eng->setRequired(true);
		$lastname_eng->addFilter(new Zend_Filter_Alpha(array('allowwhitespace' => true)));
		$lastname_eng->addValidator(new Zend_Validate_StringLength(2, 64));
		$lastname_eng->addFilter('StringTrim');

		$email = new Zend_Form_Element_Text('email');
		
		$email->setRequired(true);
		$email->setLabel($translate->_('Email'));
		$email->addValidator('EmailAddress');
		//$email->addValidator(new Admin_Validate_Email());
		$email->addValidator(new Zend_Validate_StringLength(3, 64));
		$email->addFilter('StringTrim');
		
		$telephone = new Zend_Form_Element_Text('telephone');
		
		$telephone->setRequired(true);
		$telephone->setLabel($translate->_('Telephone'));
		$telephone->addFilter('StripTags');
		$telephone->addValidator(new Zend_Validate_StringLength(3, 32));
		$telephone->addFilter('StringTrim');

		$skype = new Zend_Form_Element_Text('skype');
		
		$skype->setRequired(true);
		$skype->setLabel($translate->_('Skype'));
		$skype->addFilter('StripTags');
		$skype->addValidator(new Zend_Validate_StringLength(3, 32));
		$skype->addFilter('StringTrim');
		
		$day = new Zend_Form_Element_Select('day');
		$day->setLabel($translate->_('Birth date'));
		$day->setRequired(true);
		$day->addValidator(new Zend_Validate_GreaterThan(0));

		$day->addMultiOption(0, '');
		for($i = 1; $i <= 31; $i++) {
			$day->addMultiOption($i, $i);
		}
		
		$month = new Zend_Form_Element_Select('month');
		$month->addFilter('Digits');
		$month->setRequired(true);
		$month->addValidator(new Zend_Validate_GreaterThan(0));
		
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
		
		$year->setRequired(true);
		$year->addValidator(new Zend_Validate_GreaterThan(0));
		$year->addMultiOption(0, '');
		for($i = 2012; $i >= 1940; $i--) {
			$year->addMultiOption($i, $i);
		}
		
		//$date_error = new Zend_Form_Element_Hidden('date_error');
		//$date_error->setDecorators(array('ViewHelper', 'Errors', new Site_Form_Decorator_Profile_Date_Error()));
		
		
		$city = new Zend_Form_Element_Text('city');
		
		$city->setLabel($translate->_('City'));
		$city->addFilter(new Zend_Filter_Alnum(array('allowwhitespace' => true)));
		$city->addValidator(new Zend_Validate_StringLength(3, 64));
		$city->addFilter('StringTrim');
		
		$height = new Zend_Form_Element_Text('height');
		
		$height->setLabel($translate->_('Height (cm)'));
		$height->addFilter(new Zend_Filter_Digits());
		
		/*$weight = new Zend_Form_Element_Text('weight');
		$weight->setDecorators(array('ViewHelper', new Site_Form_Decorator_Profile_Text()));
		$weight->setLabel($translate->_('Weight (kg)'));
		$weight->addFilter(new Zend_Filter_Digits());*/
		
		//$this->addElements(array($telephone, $skype, $day, $month, $year, $date_error));
		//$username, $lastname, $username_eng, $lastname_eng,
		//$this->addDisplayGroup(array('name', 'lastname', 'name_eng', 'lastname_eng'), 'group1', array('legend' => $translate->_('Name')));
		//$this->addDisplayGroup(array('telephone', 'skype'), 'group2', array('legend' => $translate->_('Contact info')));
		//$this->addDisplayGroup(array('day', 'month', 'year', 'date_error'), 'group3', array('legend' => $translate->_('Birth date')));
		
		//$this->addElements(array($city));
		
		$db = new Application_Model_Profileoption();
		$options = $db->getOptionsForEdit();
		//var_dump($options);
		$option_list = array('step1' => array(), 'step2' => array(), 'step3' => array());
		foreach($options as $option) {
			if($option['type'] == 'select') {
				$newSelect = new Zend_Form_Element_Select('profile_select' . $option['profile_option_id']);
				//$option_list[] = 'option' . $option['profile_option_id'];
				
				$newSelect->setLabel($option['name']);
				
				$values = $db->getValues($option['profile_option_id']);
				
				$newSelect->addMultiOption(0, $translate->_('Not selected'));
				foreach($values as $value) {
					$newSelect->addMultiOption($value['profile_option_value_id'], $value['name']);
				}
				
				$option_list[$option['section']][] = $newSelect;
				
			}elseif($option['type'] == 'checkbox') {
				$newCheckbox = new Zend_Form_Element_MultiCheckbox('profile_checkbox' . $option['profile_option_id']);
				
				$newCheckbox->setLabel($option['name']);
				
				$values = $db->getValues($option['profile_option_id']);
				
				foreach($values as $value) {
					$newCheckbox->addMultiOption($value['profile_option_value_id'], $value['name']);
				}
				
				$option_list[$option['section']][] = $newCheckbox;

			}
		}
		
		//languages
		$languages = new Zend_Form_SubForm('language');
		//$languages->setDecorators(array(new Site_Form_Decorator_Profile_Languages()));
		
		$db_user = new Application_Model_Users();
$request = Zend_Controller_Front::getInstance()->getRequest();
		$user = $db_user->getUser($request->getParam('user_id'));
		
		$st = $request->getParam('step1');
		
		if(isset($st['languages'])) {
			$languageCount = count($st['languages']) / 2;
		}elseif($user) {
			$languageCount = count($db_user->getLanguagesByUserId($request->getParam('user_id')));
		}else $languageCount = 1;
		
		if($languageCount < 1) $languageCount = 1;
		
		$db_language = new Application_Model_Language();
		$all_languages = $db_language->getAllLanguages();
		$skills = $db_language->getSkills();
		
		$language_skills_names = array();
		
		for($c = 0; $c < $languageCount; $c++) {
			$language = new Zend_Form_Element_Select('language' . $c, array('belongsTo' => 'languages'));
			//$language_skills_names[] = 'language' . $c;
			$language->setDecorators(array('ViewHelper'));
			$language->setLabel($translate->_('Language / skill'));
			
			$language->addMultiOption(0, $translate->_('Not selected'));
			foreach($all_languages as $lang) {
				$language->addMultiOption($lang['all_language_id'], $lang['name']);
			}
			
			$languages->addElement($language);
			//$this->addElement($language);
			
			$skill = new Zend_Form_Element_Select('skill' . $c, array('belongsTo' => 'languages'));
			//$language_skills_names[] = 'skill' . $c;
			$skill->setDecorators(array('ViewHelper'));
			
			$skill->addMultiOption(0, $translate->_('Not selected'));
			foreach($skills as $sk) {
				$skill->addMultiOption($sk['skill_id'], $sk['name']);
			}
			
			$languages->addElement($skill);
			//$this->addElement($skill);
		}
		
		//$this->addSubForm($languages, 'languages');

		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel($translate->_('Save'));
		//$submit->setDecorators(array('ViewHelper', new Site_Form_Decorator_Profile_Submit()));
		
		/*$home = new Zend_Form_Element_Checkbox('home');
		$home->setDecorators(array('ViewHelper', new Site_Form_Decorator_Profile_Text()));
		$home->setLabel($translate->_('Allow image on the main page'));
		$home->addFilter(new Zend_Filter_Digits());*/
		
		//$this->addDisplayGroup(array_merge(array('languages', 'telephone', 'skype', 'city', 'day', 'month', 'year', 'date_error', 'languages'), $option_list, $language_skills_names), 'step1', array('legend' => $translate->_('Name')));
		$step1 = new Zend_Form_SubForm('step1');
		$step1->addElements(array_merge(array($email, $languages, $telephone, $skype, $city, $height, $day, $month, $year, /*$date_error,*/ $languages), $option_list['step1']/*, array($home)*/));
		//$step1->setDecorators(array('FormElements',	new Site_Form_Decorator_Profile_Step()));
		
		$step1->setLegend($translate->_('Name'));
		$step1->addSubForm($languages, 'languages');
		$this->addSubForm($step1, 'step1');
		//'name', 'lastname', 'name_eng', 'lastname_eng', 
		// $weight,
		
		$step2 = new Zend_Form_SubForm('step2');
		$step2->addElements($option_list['step2']);
		//$step2->setDecorators(array('FormElements',	new Admin_Form_Decorator_Profile_Step()));
		$step2->setLegend($translate->_('Personal information'));
		$this->addSubForm($step2, 'step2');
		
		$step3 = new Zend_Form_SubForm('step3');
		$step3->addElements($option_list['step3']);
		//$step3->setDecorators(array('FormElements',	new Admin_Form_Decorator_Profile_Step()));
		$step3->setLegend($translate->_('Preferences'));
		$this->addSubForm($step3, 'step3');
		
		
		$this->addElements(array($submit));
		
	}
}