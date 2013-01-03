<?php
class Site_Form_Decorator_Profile_Settings extends Zend_Form_Decorator_Abstract {
	
	public function render($content) {
		
		return '
			<table>' . $content . '</table>
		';
	}
}