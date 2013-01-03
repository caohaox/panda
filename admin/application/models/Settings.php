<?php
class Application_Model_Settings extends Admin_Db {
	
	public function getSettings() {
		$query = $this->db->select()
				 		  ->from(array('s' => 'settings'))
				 		  ->joinLeft(array('sd' => 'settings_description'), 's.setting_id = sd.setting_id', array('sd.label'))
				 		  ->where('sd.language_id = ?', Zend_Registry::get('language_id'));
		
		return $this->db->fetchAll($query);
	}
	
	/**
	 * update settings
	 *
	 * @param $data array(setting_name => value)
	 */
	public function updateSettings($data) {
		
		foreach($data as $setting_name => $value) {
			
			$this->db->update('settings', array('value' => $value), 'setting_name = ' . $this->db->quote($setting_name));
		
		}
		return true;
	}
}