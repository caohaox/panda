<?php
class Site_Form_Decorator_Profile_Date_Year extends Zend_Form_Decorator_Abstract {
	
	public function render($content) {
		
		$element = $this->getElement();
		$view = $element->getView();
	
		
		return '
					<div class="m_width70">' . $content . '</div>
				</td>
			</tr>
		';
	}
}