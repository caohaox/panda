<?php
class Site_Form_Decorator_Agree extends Zend_Form_Decorator_Abstract {
	
	public function render($content) {
		
		$element = $this->getElement();
		$view = $element->getView();	
		$name = $view->escape($element->getName());
		$errors = $this->buildErrors();
		return '
			<div class="agree">
				<span>' . $content . '</span>
                <label for="' . $name . '">
                    Я согласен с <a class="blue" href="#" target="_blank">договором об обслуживании</a>
                </label>
                    ' . $errors . '
                <p>Поля отмеченые символом (*) обязательны.</p>
            </div>
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