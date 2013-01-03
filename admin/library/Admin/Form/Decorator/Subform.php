<?php
class Admin_Form_Decorator_Subform extends Zend_Form_Decorator_Abstract {
	
	public function render($content) {
		
		$group = $this->getElement();
		$elements = $group->getElements();
		$view = $group->getView();
		
		
		$return = '
<div class="m_tab" id="language' . $group->getName() . '">
	<div class="item_area">';
		$return .= '<h2>' . $group->getLegend() . '</h2>';
		foreach($elements as $key => $element) { 
				$return .= '
				
						' . $element->render() . '
					';
		}
		
	$return .= '</div>
</div>
		';
		
		return $return;
	}
}