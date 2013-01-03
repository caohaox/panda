<?php
class Site_Form_Decorator_SpamSubmit extends Zend_Form_Decorator_Abstract {
	
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
								parent = $(\'[name="' . $form_name . '"]\').parent();
								$(\'#id_' . $form_name . '\').attr(\'onclick\', "subm' . $form_name . '();");
								$(\'[name="' . $form_name . '"]\').hide();
								$(parent).find(\'h1\').html(output[\'success\']);
							}
						}
					});
				}
			</script>
		';
	}
}