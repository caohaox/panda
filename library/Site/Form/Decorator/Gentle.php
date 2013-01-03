<?php
class Site_Form_Decorator_Gentle extends Zend_Form_Decorator_Abstract {
	
	public function render($content) {
		$translate = Zend_Registry::get('translate');
		return '
              	<dl>
                    <dt><label for="genderG">' . $translate->_('I’m gender') . '</label></dt>
                    <dd><input type="text" id="genderG" name="genderG" value="' . $translate->_('Male') . '" readonly class="nonEditable" /></dd>
                </dl>
                <dl>
                    <dt><label for="countryG">' . $translate->_('Country') . '</label></dt>
                    <dd><input type="text" id="countryG" name="countryG" value="' . $translate->_('China') . '" readonly class="nonEditable" /></dd>
                </dl> 
		';
	}
}