<?php
class Site_Form_Decorator_Profile_Step extends Zend_Form_Decorator_Abstract {
	
	public function render($content) {
		
		$element = $this->getElement();
		
		return '
		<li>
			<h3>' . $element->getLegend() . '</h3>
			<div class="acc-section">
				<div class="acc-content"><table>' . $content . '</table></div>
			</div>
		</li>';
	}
}