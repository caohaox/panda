<?php
class Site_Form_Decorator_ForgetPassword extends Zend_Form_Decorator_Abstract {
	
	public function render($content) {
		
		$element = $this->getElement();
		$view = $element->getView();	
		$label = $view->escape($element->getLabel());

		return '
			<a class="forget_href" href="' . $view->baseUrl('register/forget') . '">' . $label . '</a>
		';
	}
}