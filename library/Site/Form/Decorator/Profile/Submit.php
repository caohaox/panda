<?php
class Site_Form_Decorator_Profile_Submit extends Zend_Form_Decorator_Abstract {
	
	public function render($content) {
		
		$element = $this->getElement();
		$view = $element->getView();

		return '
<li><h3><a class="profile_button" onclick="$(\'#steps_form\').submit();">' . $element->getLabel() . '</a></h3><div class="acc-section"><div class="acc-content"></div></div></li>
		';
	}	
}