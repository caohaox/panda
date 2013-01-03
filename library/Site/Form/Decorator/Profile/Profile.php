<?php
class Site_Form_Decorator_Profile_Profile extends Zend_Form_Decorator_Abstract {
	
	public function render($content) {
		
		return '
			<ul class="acc" id="acc">' . $content . '</ul>
		';
	}
}