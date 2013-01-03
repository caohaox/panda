<?php
class Site_Form_Decorator_Step1Image extends Zend_Form_Decorator_Abstract {
	
	public function render($content) {
		
		$element = $this->getElement();
		$view = $element->getView();
		$label = $view->escape($element->getLabel());	
		$name = $view->escape($element->getName());
		$errors = $this->buildErrors();
		
		return '
	<dl>
		<dt><label for="profile_photo">' . $label . '</label></dt>
		<dd>' . $content . '' . $errors . '</dd>
	</dl>
	<div class="clear"></div>
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