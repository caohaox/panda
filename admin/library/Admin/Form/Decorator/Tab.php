<?php
class Admin_Form_Decorator_Tab extends Zend_Form_Decorator_Abstract {
	
	public function render($content) {
		
		$group = $this->getElement();
		$elements = $group->getElements();
		$view = $group->getView();
		
		
		$return = '

	<div class="m_tabs">';
		foreach($elements as $key => $element) { 
				$return .= '
				
						<a id="#' . $element->getName() . '">' . $element->getAttrib('language_name') . '</a>
					';
		}
		
	$return .= '</div>

		';
		
		return $return;
	}
}