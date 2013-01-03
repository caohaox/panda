<?php
class Site_Form_Decorator_DoubleText2 extends Zend_Form_Decorator_Abstract {
	
	public function render($content) {
		
		$element = $this->getElement();
		$view = $element->getView();
		$label = $view->escape($element->getLabel());	
		$name = $view->escape($element->getName());
		$errors = $this->buildErrors();
		//if($element->isRequired()) {$required = ' *';}else $required = '';
		$required = '';
		return '
                	<div>' . $content . '' . $errors . '</div>
                </dd>
           </dl>
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