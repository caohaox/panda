<?php
class Site_Form_Decorator_Profile_Date_Day extends Zend_Form_Decorator_Abstract {
	
	public function render($content) {
		
		$element = $this->getElement();
		$view = $element->getView();
		$label = $view->escape($element->getLabel());	
		$name = $view->escape($element->getName());
		
		if($element->isRequired()) {$required = ' *';}else $required = '';
		
		return '
		<tr>
			<td class="pInfoTableBlockT" colspan="2">
				<div>' . $label . '</div>
			</td>
		</tr>
		<tr>
			<td class="pInfoParamT" colspan="2">
                <div class="m_width70">' . $content . '</div>
		';
	}

}