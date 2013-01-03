<?php
class Admin_Form_Decorator_Relatedvalue extends Zend_Form_Decorator_Abstract {
	
	public function render($content) {
		
		$element = $this->getElement();
		$view = $element->getView();	
		$label = $view->escape($element->getLabel());
		$name = $view->escape($element->getName());
		$errors = $this->buildErrors();
		$db = new Application_Model_Profileoption();
		
		
		
		$output = '<option value="0"></option>';
		
		$optionValue = $db->getOptionValue($element->getValue());
		
		if($optionValue) {
			$values = $db->getValues($optionValue['profile_option_id']);
			foreach($values as $value) {
				if($element->getValue() == $value['profile_option_value_id']) {
					$output .= '<option value="' . $value['profile_option_value_id'] . '" selected="selected">' . $value['name'] . '</option>';
				}else {
					$output .= '<option value="' . $value['profile_option_value_id'] . '">' . $value['name'] . '</option>';
				}
			}
		}
		$return = '
			<div class="text">
                <label for="' . $name . '">
	                ' . $label . '
                </label>
                <div class="related_value">';
		
		$return .= '<select id="option_list" onchange="getOptionValues($(this).val());"><option value="0"></option>';

		$options = $db->getFullOptions();
		
		foreach($options as $option) {
			if($optionValue && ($optionValue['profile_option_id'] == $option['profile_option_id'])) {
				$return .= '<option value="' . $option['profile_option_id'] . '" selected="selected">' . $option['section'] . ' - ' . $option['name'] . '</option>';
			}else {
				$return .= '<option value="' . $option['profile_option_id'] . '">' . $option['section'] . ' - ' . $option['name'] . '</option>';
			}
		}
			
        $return .= '
                </select><br />';
		
        $return .= '<select id="list_value" onchange="$(\'#related_option_value\').val($(this).val());">' . $output . '"</select>
                	' . $content . '
                </div>
                ' . $errors . '';
    	
        $return .= '
                <script type="text/javascript">
                	
                	
                	function getOptionValues(value) {
                		if(value == 0) {
                			$(\'#related_option_value\').val(0);
                		} 
                		$.ajax({
                			url: "' . $view->baseUrl('options/getvalues/profile_option_id/') . '" + value,
                			success: function(output) {
                				$("#list_value").html(output);
                			}
                		});
                	}
                	
                	
                </script>
            </div>
		';
        
        return $return;
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