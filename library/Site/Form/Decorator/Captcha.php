<?php
class Site_Form_Decorator_Captcha extends Zend_Form_Decorator_Abstract {
	
	public function render($content) {
		
		$element = $this->getElement();
		$view = $element->getView();
		$label = $view->escape($element->getLabel());	
		$name = $view->escape($element->getName());
		$errors = $this->buildErrors();
		$reload_action = $view->escape($element->getAttrib('reload_action'));
		if($element->isRequired()) {$required = ' *';}else $required = '';
		
		return '
                <dl class="captcha">
                    <dt><label for="' . $name . '">' . $label . $required . '</label></dt>
                    <dd>
                        ' . $content . '
                        <a onclick="reloadCaptcha(\'' . $reload_action . '\');"></a>
                        <div class="clear"></div>
                        ' . $errors . '
                    </dd>
            	</dl> 
		';
	}
	
	public function buildErrors()
    {
        $element  = $this->getElement();
        $messages = $element->getMessages();
        if (empty($messages)) {
            return '';
        }
        return $element->getView()->formErrors($messages);
    }
}