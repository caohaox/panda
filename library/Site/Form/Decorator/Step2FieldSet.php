<?php
class Site_Form_Decorator_Step2FieldSet extends Zend_Form_Decorator_Abstract {
	
	public function render($content) {	
		$element = $this->getElement();
		return '
		<fieldset class="personalInfoFieldSet">
			<legend>' . $element->getLegend() . '</legend>
	       ' . $content . '
        </fieldset>
		';
	}

}