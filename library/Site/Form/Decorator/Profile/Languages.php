<?php
class Site_Form_Decorator_Profile_Languages extends Zend_Form_Decorator_Abstract {
	
	public function render($content) {
		
		$group = $this->getElement();
		$elements = $group->getElements();
		$view = $group->getView();
		$translate = Zend_Registry::get('translate');
		
		$return = '
		<tr>
        	<td class="pInfoTableBlockT" colspan="2"><div>' . $translate->_('Languages') . '</div></td>
        </tr>';
		$lastKey = 0;
		$c = 0;
		foreach($elements as $key => $element) { 
			if(strpos($key, 'language') !== false) {
				$return .= '
		<tr>
        	<td class="pInfoParamT"><label for="language' . $c . '">' . $element->getLabel() . '</label></td>
        	<td>
				<div class="repeatableFItem">
					<dl>
						<dt></dt>
						<dd>
							<div class="m_width164">
								' . $element->render() . '
							</div>';
			}else {
				$return .= '
							<div class="m_width164">' . $element->render() . '</div>
						</dd>
					</dl>
					<div class="clear"></div>
				</div>
			</td>
		</tr>';
				$c++;
			}
			$lastKey = $c;
		}
		$return .= '
	<tr class="language_button_here">
		<td>
			<div class="button"><a class="add_language" onclick="addLanguageProfile(' . $lastKey . ')">+ ' . $translate->_('more languages') . '</a></div>
		</td>
    </tr>';
		
		return $return;
	}
}