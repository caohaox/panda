<?php
/**
 * Class inserts neccery code for initialize rich text editor ElRTE
 */
class Zend_View_Helper_EnableElRTE extends Zend_View_Helper_Abstract{

	public function enableElRTE() {
		$elrte_base_uri = $this->view->baseUrl('/js/elrte-1.3/');

		$this->view->headLink()->appendStylesheet("{$elrte_base_uri}css/smoothness/jquery-ui-1.8.13.custom.css");
		$this->view->headScript()->appendFile("{$elrte_base_uri}js/jquery-ui-1.8.13.custom.min.js");
		
		$this->view->headScript()->appendFile("{$elrte_base_uri}js/elrte.min.js");
		$this->view->headLink()->appendStylesheet("{$elrte_base_uri}css/elrte.min.css");
		$this->view->headScript()->appendFile("{$elrte_base_uri}js/i18n/elrte.en.js");
		$this->view->headLink()->appendStylesheet($this->view->baseUrl('js/ui/themes/ui-lightness/ui.all.css'));
   	}
}