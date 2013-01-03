<?php
class Site_Form_Decorator_Profile_SettingsText extends Zend_Form_Decorator_Abstract {
	
	public function render($content) {
		
		$element = $this->getElement();
		$view = $element->getView();
		$label = $view->escape($element->getLabel());	
		$name = $view->escape($element->getName());
		$errors = $this->buildErrors();
		//if($element->isRequired()) {$required = ' *';}else $required = '';
		$required = '';
		return '
                
                    <div class="m_width164 f_l" style="clear: both;">' . $label . $required . '</div>
                    <div class="m_width164 f_l">' . $content . '' . $errors . '</div>
                
		';
	}
	
	public function buildErrors()
    {
        $element  = $this->getElement();
        $messages = $element->getMessages();
        if (empty($messages)) {
            return '';
        }
        return $element->getView()->formErrors($messages);
    }
}