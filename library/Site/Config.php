<?php
class Site_Config {
	
	public function get($setting) {
		if(!Zend_Registry::isRegistered($setting)) {
			$db = Zend_Registry::get('db');
			$select = $db->query('SELECT value FROM settings WHERE setting_name = ?', $setting);
			$result = $select->fetchColumn();
			
			Zend_Registry::set($setting, $result);
			
			return $result;
		}
		
		return Zend_Registry::get($setting);
	}
}