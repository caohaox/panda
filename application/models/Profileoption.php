<?php 

class Application_Model_Profileoption extends Site_Db {	
	
	public function getOptions($data = array()) {
		$sql = $this->db->select()
						->from(array('po' => 'profile_option'))
						->joinLeft(array('pod' => 'profile_option_description'), 'po.profile_option_id = pod.profile_option_id')
						->where('pod.language_id = ?', Zend_Registry::get('language_id'));

		if(isset($data['section'])) $sql->where('po.section = ?', $data['section']);
		if(isset($data['sections'])) {
			foreach($data['sections'] as $section) $section = $this->db->quote($section);
			$sql->where("po.section IN ('" . implode("','", $data['sections']) . "')");
		}
		if(isset($data['for_man']) && $data['for_man']) $sql->where('po.for_man = ?', $data['for_man']);
		if(isset($data['for_woman']) && $data['for_woman']) $sql->where('po.for_woman = ?', $data['for_woman']);
		$sql->order('po.sort_order');				
		return $this->db->fetchAll($sql);
	}
	
	
	public function getValues($profile_option_id) {
		$sql = $this->db->select()
						->from(array('pov' => 'profile_option_value'))
						->joinLeft(array('povd' => 'profile_option_value_description'), 'pov.profile_option_value_id = povd.profile_option_value_id')
						->where('pov.profile_option_id = ?', $profile_option_id)
						->where('povd.language_id = ?', Zend_Registry::get('language_id'))
						->order('pov.sort_order ASC')
						->order('povd.name ASC');

		//var_dump((string)$sql);
		//exit;
		return $this->db->fetchAll($sql);
	}
	
	
	public function getOptionsByUserId($user_id) {
		$sql = $this->db->select()
						->from(array('uov' => 'user_option_value'))
						->joinLeft(array('pov' => 'profile_option_value'), 'uov.profile_option_value_id = pov.profile_option_value_id')
						->joinLeft(array('povd' => 'profile_option_value_description'), 'pov.profile_option_value_id = povd.profile_option_value_id', array('povd.*', 'value_name' => 'povd.name'))
						->joinLeft(array('po' => 'profile_option'), 'pov.profile_option_id = po.profile_option_id')
						->joinLeft(array('pod' => 'profile_option_description'), 'po.profile_option_id = pod.profile_option_id', array('pod.*', 'option_name' => 'pod.name'))
						->where('uov.user_id = ?', $user_id)
						->where('pod.language_id = ?', Zend_Registry::get('language_id'))
						->where('povd.language_id = ?', Zend_Registry::get('language_id'))
						->order('po.sort_order ASC');
				
		return $this->db->fetchAll($sql);
	}
	
	public function getRegion($user_id, $is_man = 1) {
		
		if($is_man) {
			$region = Zend_Registry::get('region_man');
		}else $region = Zend_Registry::get('region_woman');
		
		$sql = $this->db->select()
						->from(array('uov' => 'user_option_value'), array())
						->joinLeft(array('povd' => 'profile_option_value_description'), 'uov.profile_option_value_id = povd.profile_option_value_id', 'name')
						->where('uov.profile_option_id = ?', $region)
						->where('uov.user_id = ?', $user_id)
						->where('povd.language_id = ?', Zend_Registry::get('language_id'));
		$res = $this->db->fetchRow($sql);
		return $res['name'];		
	}
	
	public function getPartnerAge($user_id) {

		$sql = $this->db->select()
						->from(array('uov' => 'user_option_value'), array())
						->joinLeft(array('povd' => 'profile_option_value_description'), 'uov.profile_option_value_id = povd.profile_option_value_id', 'name')
						->where('uov.profile_option_id = ?', Zend_Registry::get('partner_age'))
						->where('uov.user_id = ?', $user_id)
						->where('povd.language_id = ?', Zend_Registry::get('language_id'));
		$res = $this->db->fetchRow($sql);
		return $res['name'];		
	}
	
	public function getSfera($user_id) {

		$sql = $this->db->select()
						->from(array('uov' => 'user_option_value'), array())
						->joinLeft(array('povd' => 'profile_option_value_description'), 'uov.profile_option_value_id = povd.profile_option_value_id', 'name')
						->where('uov.profile_option_id = ?', Zend_Registry::get('sfera'))
						->where('uov.user_id = ?', $user_id)
						->where('povd.language_id = ?', Zend_Registry::get('language_id'));
		$res = $this->db->fetchRow($sql);
		return $res['name'];		
	}
}