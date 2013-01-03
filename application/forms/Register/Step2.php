<?php 
class Application_Form_Register_Step2 extends Zend_Form {
	
	public function __construct() {
		
		$translate = Zend_Registry::get('translate');
		$request = Zend_Controller_Front::getInstance()->getRequest();
 		
		$this->setDisableLoadDefaultDecorators(true);
		
 
        $this->addDecorator('FormElements')
        	 ->addDecorator(new Site_Form_Decorator_Step2FieldSet())
        	 ->addDecorator('Form');
        	 
		$view = $this->getView();
		
		$this->setAction($view->baseUrl('steps/step2'));
		$this->setMethod('post');
		$this->setLegend($translate->_('Personal information'));
		
		$this->setName('step2_form');
		
		$db = new Application_Model_Profileoption();
		$options = $db->getOptions(array('section' => 'step2'));
		
		foreach($options as $option) {
			if($option['type'] == 'select') {
				$newSelect = new Zend_Form_Element_Select('profile_select' . $option['profile_option_id']);
				$newSelect->setDecorators(array('ViewHelper', new Site_Form_Decorator_Text()));
				$newSelect->setLabel($option['name']);
				
				$values = $db->getValues($option['profile_option_id']);
				
				$newSelect->addMultiOption(0, $translate->_('Not selected'));
				foreach($values as $value) {
					$newSelect->addMultiOption($value['profile_option_value_id'], $value['name']);
				}
				
				$this->addElements(array($newSelect));
			}elseif($option['type'] == 'checkbox') {
				$newCheckbox = new Zend_Form_Element_MultiCheckbox('profile_checkbox' . $option['profile_option_id']);
				$newCheckbox->setDecorators(array('ViewHelper', new Site_Form_Decorator_Checkbox()));
				$newCheckbox->setLabel($option['name']);
				
				$values = $db->getValues($option['profile_option_id']);
				
				foreach($values as $value) {
					$newCheckbox->addMultiOption($value['profile_option_value_id'], $value['name']);
				}
				
				$this->addElements(array($newCheckbox));
			}
		}
		
		
		
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel($translate->_('Continue'));
		$submit->setDecorators(array('ViewHelper', new Site_Form_Decorator_Submitback()));
		$submit->setAttrib('back', $view->baseUrl('steps/step1'));
		
		$this->addElements(array($submit));

	}
}