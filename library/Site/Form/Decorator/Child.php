<?php
class Site_Form_Decorator_Child extends Zend_Form_Decorator_Abstract {
	
	public function render($content) {
		
		$group = $this->getElement();
		$elements = $group->getElements();
		$view = $group->getView();
		$translate = Zend_Registry::get('translate');
		
		$return = '';
		
		foreach($elements as $element) { 
			$return .= '
					<dl>
						<dt><label for="' . $view->escape($element->getId()) . '">' . $view->escape($element->getLabel()) . '</label></dt>
						<dd>
								' . $element->render() . '
						</dd>
					</dl>';

		}

			
		return $return;
	}
}