<?php

class IndexController extends Zend_Controller_Action {
	
	public function indexAction() {
		
	}
	
	//смена языка
	public function languageAction() {
		
		
    	$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout()->disableLayout();
		
		if($this->_getParam('language_id')) {
			$session = new Zend_Session_Namespace('admin_area');
			$db = Zend_Registry::get('db');
			$query = $db->select()
						   ->from('language')
						   ->where('language_id = ?', $this->_getParam('language_id'));
			
			$language = $db->fetchRow($query);
			
			if($language) {
				Zend_Registry::set('language_id', $language['language_id']);
		
				$session->language = $language;
				setcookie('admin_language_id', $language['language_id'], time() + 60 * 60 * 24 * 30, '/', $_SERVER['HTTP_HOST']);
			}
		}
	}
	
	
	public function tempAction() {
		
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout()->disableLayout();
		
		//добавление языклв
		/*$languages = array(
			array('Английский', 'English', '英语'),
			array('Арабский', 'Arabic', '阿拉伯语'),
			array('Белорусский', 'Belorussian', '白俄罗斯语'),
			array('Болгарский', 'Bulgarian', '保加利亚语'),
			array('Венгерский', 'Hungarian', '匈牙利语'),
			array('Голландский	', 'Dutch', '荷兰语'),
			array('Греческий', 'Greek', '希腊语'),
			array('Датский', 'Danish', '丹麦语'),
			array('Индонезийский', 'Indonesian', '印尼语'),
			array('Испанский', 'Spanish', '西班牙语'),
			array('Итальянский', 'Italian', '意大利语'),
			array('Китайский (Кантонский)', 'Chinese (Mandarin)', '中文普通话'),
			array('Китайский (Мандарин)', 'Chinese (Cantonese)', '中文广东话'),
			array('Корейский', 'Korean', '韩语'),
			array('Немецкий', 'German', '德语'),
			array('Норвежский', 'Norwegian', '挪威语'),
			array('Польский', 'Polish', '波兰语'),
			array('Португальский', 'Portuguese', '葡萄牙语'),
			array('Румынский', 'Romanian', '罗马尼亚语'),
			array('Русский', 'Russian', '俄语'),
			array('Сербско-хорватский', 'Serbian-Croatian', '塞维语'),
			array('Словацкий', 'Slovak', '斯拉夫语'),
			array('Тайский', 'Thai', '泰语'),
			array('Турецкий', 'Turkish', '土耳其语'),
			array('Украинский', 'Ukrainian', '乌克兰语'),
			array('Фарси', 'Persian', '波斯语'),
			array('Финский', 'Finnish', '芬兰语'),
			array('Французский', 'French', '法语'),
			array('Хинди', 'Hindi', '印度语'),
			array('Чешский', 'Czech', '捷克语'),
			array('Шведский', 'Swedish', '瑞典语'),
			array('Японский', 'Japanese', '日语'),
		);
		
		$db = Zend_Registry::get('db');
		
		foreach($languages as $language) {
			$db->insert('all_languages', array());
			
			$language_id = $db->lastInsertId();
			
			$i = 0;
			foreach($language as $l) {
				$i++;
				$db->insert('all_languages_names', array('all_language_id' => $language_id, 'language_id' => $i, 'name' => $l));
			}
		}*/
	}
}