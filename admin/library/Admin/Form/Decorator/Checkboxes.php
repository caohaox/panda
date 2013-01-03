<?php
class Admin_Form_Decorator_Checkboxes extends Zend_Form_Decorator_Abstract {
	
	public function render($content) {
		
		$element = $this->getElement();
		$view = $element->getView();	
		$label = $view->escape($element->getLabel());
		$name = $view->escape($element->getName());
		$errors = $this->buildErrors();
		return '
			<div class="checkbox checkboxes">
				<label>' . $label . '</label>
				<br />
				' . $content . '
                ' . $errors . '
            </div>
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