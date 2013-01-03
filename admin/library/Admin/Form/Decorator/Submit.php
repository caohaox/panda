<?php
class Admin_Form_Decorator_Submit extends Zend_Form_Decorator_Abstract {
	
	public function render($content) {
		
		$element = $this->getElement();
		$view = $element->getView();
		$label = $view->escape($element->getLabel());	
		$name = $view->escape($element->getName());
		
		return '
		<div class="button">
			' . $content . '
		</div>
		';
	}
}