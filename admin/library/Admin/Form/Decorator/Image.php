<?php
class Admin_Form_Decorator_Image extends Zend_Form_Decorator_Abstract {
	
	public function render($content) {
		
		$element = $this->getElement();
		$view = $element->getView();
		$label = $view->escape($element->getLabel());	
		$name = $view->escape($element->getName());
		$errors = $this->buildErrors();
		$translate = Zend_Registry::get('translate');
		
		return '
		<div class="choose-image-wrapper">	
                    ' . $content . '
            <label>' . $label . '</label>
            <div class="choose-image">
				<img style="width: 150px; height: 150px;" src="' . Admin_Config::get('site_url'). 'img/data/' . $element->getValue() . '" />
			</div>
                    ' . $errors . '
            <script type="text/javascript">
			$(document).ready(function () {
				$(\'.choose-image\').live(\'click\', function() {
					$(this).append(\'<div id="filo"></div>\');
					var imageDiv = this;
					$(\'#filo\').elfinder({
						url : \'' . $view->baseUrl('file/manager') . '\',
						lang : \'ru\',
						rememberLastDir : true,
						contextmenu : {
							cwd : [\'reload\', \'delim\', \'mkdir\', \'mkfile\', \'upload\', \'delim\', \'paste\', \'delim\', \'info\'], 
							file : [\'select\', \'open\', \'delim\', \'copy\', \'cut\', \'rm\', \'delim\', \'duplicate\', \'rename\'], 
							group : [\'copy\', \'cut\', \'rm\', \'delim\', \'archive\', \'extract\', \'delim\', \'info\'] 
						},
						dialog : { title : \'' . $translate->_('Select image') . '\', width : 900, modal : true },
						editorCallback : function(url) {
							$(\'[name="' . $name . '"]\').val(url.replace("' . Admin_Config::get('site_url') . 'img/data/' . '", ""));	
							$(imageDiv).children(\'img\').attr(\'src\', url);	
						},
						closeOnEditorCallback : true
					});
				});
			});
			</script>    
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