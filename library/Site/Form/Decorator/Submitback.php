<?php
class Site_Form_Decorator_Submitback extends Zend_Form_Decorator_Abstract {
	
	public function render($content) {
		
		$element = $this->getElement();
		$view = $element->getView();
		$translate = Zend_Registry::get('translate');
		$back = $element->getAttrib('back');
		
		return '
<div class="clear"></div>
<div class="fControls"><div class="button"><a href="' . $back . '">' . $translate->_('Back') . '</a></div>' . $content . '</div><br />
		';
	}	
}