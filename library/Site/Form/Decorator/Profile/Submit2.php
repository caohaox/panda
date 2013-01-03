<?php
class Site_Form_Decorator_Profile_Submit2 extends Zend_Form_Decorator_Abstract {
	
	public function render($content) {
		
		$element = $this->getElement();
		$view = $element->getView();
		$action = $element->getAttrib('form_name');

		return '
<div style="text-align: center; margin-top: 10px;">
<a class="button_gold" onclick="$(\'#' . $action . '\').submit();"><span>' . $element->getLabel() . '</span></a></div>
		';
	}	
}