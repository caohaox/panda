<?php
/**
 * Class inserts neccery code for initialize file manager ElFinder
 */
class Zend_View_Helper_EnableElFinder extends Zend_View_Helper_Abstract{

	public function enableElFinder() {
		$elfinder_base_uri = $this->view->baseUrl('/js/elfinder-1.2/');

		$this->view->headLink()->appendStylesheet("{$elfinder_base_uri}css/elfinder.css");
		$this->view->headScript()->appendFile("{$elfinder_base_uri}js/elfinder.min.js");
		$this->view->headScript()->captureStart() ?>
			var opts = {
				cssClass : 'el-rte',
				lang 	 : 'ru',
				height 	 : 250,
				toolbar  : 'complete',
				cssfiles : ['css/elrte-inner.css'],
				fmAllow  : true,
				fmOpen   : function(callback) {
						$('<div id="myelfinder" />').elfinder({
						   	url : '<?php echo $this->view->baseUrl('/file/manager'); ?>',
							lang : 'ru',
							dialog : { width : 900, modal : true, title : 'FILES' }, // открываем в диалоговом окне
							closeOnEditorCallback : true, // закрываем после выбора файла
							editorCallback : callback
			            });
				}
			}
		<?php $this->view->headScript()->captureEnd();
   	}
}