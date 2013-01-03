<?php
class Site_Form_Decorator_Profile_Date_Error extends Zend_Form_Decorator_Abstract {
	
	public function render($content) {
		
		$element = $this->getElement();
		$view = $element->getView();

		return '
		<tr>
			<td colspan="2">
			' . $content . '
			</td>
		</tr>
		';
	}
}