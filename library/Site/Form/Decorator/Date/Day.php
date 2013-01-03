<?php
class Site_Form_Decorator_Date_Day extends Zend_Form_Decorator_Abstract {
	
	public function render($content) {
		
		$element = $this->getElement();
		$view = $element->getView();
		$label = $view->escape($element->getLabel());	
		$name = $view->escape($element->getName());
		
		if($element->isRequired()) {$required = ' *';}else $required = '';
		
		return '
                <dl>
                    <dt><label for="' . $name . '">' . $label . $required . '</label></dt>
                    <dd><div class="width70">' . $content . '</div>
		';
	}

}