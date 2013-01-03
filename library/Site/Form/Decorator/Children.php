<?php
class Site_Form_Decorator_Children extends Zend_Form_Decorator_Abstract {
	
	public function render($content) {
		
		$group = $this->getElement();
		$elements = $group->getElements();
		$view = $group->getView();
		$translate = Zend_Registry::get('translate');
		
		$return = '
<fieldset id="children_here">';
	//<legend>' . $translate->_('Children') . '</legend>';
		//$return .= '';
		$return .= $content;//var_dump($content);
		$i = 0;
		foreach($elements as $el) { 
			$return .= '<div class="repeatableF"><dl><dt><label style="font-weight: bold;" for="' . $el->getId() . '">' . $translate->_('Children') . '</label></dt><dd>';
			$return .= $el->render();
			$return .= '</dd></dl><div class="clear"></div></div>';
		}
		$return .= '<div class="hide_area">';
		foreach($group->getSubForms() as $element) { 
			$return .= '<div class="repeatableF">';
			$return .= $element->render();
			$return .= '<div class="clear"></div></div>';
			$i++;
		}
		$return .= '<div class="repeatableF"><div class="button"><a class="add_child" onclick="addChild(' . $i . ')">+ ' . $translate->_('more children') . '</a></div></div></div>
</fieldset>
		';
		
		return $return;
	}
}