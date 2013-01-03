<?php
class Site_Form_Decorator_Submit extends Zend_Form_Decorator_Abstract {
	
	public function render($content) {
		
		$element = $this->getElement();
		$view = $element->getView();

		return '
<div class="clear"></div>
<div class="fControls">' . $content . '</div>
		';
	}	
}