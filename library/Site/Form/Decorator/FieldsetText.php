<?php
class Site_Form_Decorator_FieldsetText extends Zend_Form_Decorator_Abstract {
	
	public function render($content) {
		
		$element = $this->getElement();
		$view = $element->getView();
		$label = $view->escape($element->getLabel());	
		$name = $view->escape($element->getName());
		$errors = $this->buildErrors();
		//if($element->isRequired()) {$required = ' *';}else $required = '';
		$required = '';
		return '
		<fieldset class="fieldSetWithoutLegend">
	        <dl>
	            <dt><label for="' . $name . '">' . $label . $required . '</label></dt>
	            <dd>' . $content . '' . $errors . '</dd>
	        </dl>
        </fieldset>
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