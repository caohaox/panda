<?php
class Site_Form_Decorator_Date_Year extends Zend_Form_Decorator_Abstract {
	
	public function render($content) {
		
		$element = $this->getElement();
		$view = $element->getView();
	
		
		return '
					<div class="width70">' . $content . '</div></dd>
                </dl>
		';
	}
}