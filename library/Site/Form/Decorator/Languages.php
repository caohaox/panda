<?php
class Site_Form_Decorator_Languages extends Zend_Form_Decorator_Abstract {
	
	public function render($content) {
		
		$group = $this->getElement();
		$elements = $group->getElements();
		$view = $group->getView();
		$translate = Zend_Registry::get('translate');
		
		$return = '
<fieldset>
	<legend>' . $translate->_('Languages') . '</legend>
	<div class="repeatableF">';
		$lastKey = 0;
		foreach($elements as $key => $element) { 
			if(strpos($key, 'language') !== false) {
				$return .= '
				<div class="repeatableFItem">
					<dl>
						<dt><label for="' . $view->escape($element->getName()) . '">' . $view->escape($element->getLabel()) . '</label></dt>
						<dd>
							<div class="width164">
								' . $element->render() . '
							</div>';
			}else {
				$return .= '
							<div class="width164">' . $element->render() . '</div>
						</dd>
					</dl>
					<div class="clear"></div>
				</div>';
				$lastKey++;
			}
		}
		$return .= '<div class="button"><a class="add_language" onclick="addLanguage(' . ($lastKey) . ')">+ ' . $translate->_('more languages') . '</a></div>
	</div>
</fieldset>
		';
		
		return $return;
	}
}