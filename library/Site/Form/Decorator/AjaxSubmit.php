<?php
class Site_Form_Decorator_AjaxSubmit extends Zend_Form_Decorator_Abstract {
	
	public function render($content) {
		
		$element = $this->getElement();
		$view = $element->getView();
		$label = $view->escape($element->getLabel());	
		$form_name = $view->escape($element->getAttrib('form_name'));
		$action = $view->escape($element->getAttrib('action'));
		return '
            <div class="floatR"><a id="id_' . $form_name . '" class="button" onclick="subm' . $form_name . '();">
                    ' . $label . '
            </a></div>
            <script type="text/javascript">
				$(document).keydown(function(e) {
					if (e.keyCode == 13) {
						subm' . $form_name . '();
					}
				});
				
				function subm' . $form_name . '() {
					$.ajax({
						url: \'' . $action . '\',
						type: \'post\',
						dataType: \'json\',
						data: $(\'[name="' . $form_name . '"]\').serialize(),
						beforeSend: function() {
							$(\'#id_' . $form_name . '\').attr(\'onclick\', \'\');
						},
						success: function(output) {
							if(output[\'success\']) {
								location = output[\'success\'];
							}else {
								parent = $(\'[name="' . $form_name . '"]\').parent();
								$(\'[name="' . $form_name . '"]\').remove();
								$(parent).prepend(output[\'form\']);
							}
						}
					});
				}
			</script>
		';
	}
}