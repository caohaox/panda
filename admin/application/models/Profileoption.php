<?php 

class Application_Model_Profileoption extends Admin_Db {	
	
	public function getOptions() {
		$sql = $this->db->select()
						->from(array('po' => 'profile_option'))
						->joinLeft(array('pod' => 'profile_option_description'), 'po.profile_option_id = pod.profile_option_id')
						->where('pod.language_id = ?', Zend_Registry::get('language_id'));
				
		return $sql;
	}
	
	
	public function getOptionsForEdit($data = array()) {
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
	
	
	public function getTotalOptions() {
		$sql = $this->db->select()
						->from('profile_option', array('total' => 'COUNT(*)'));
						
		
		$res = 	$this->db->fetchRow($sql);
		return (int)$res['total'];
	}
	
	public function getFullOptions() {
		$sql = $this->db->select()
						->from(array('po' => 'profile_option'))
						->joinLeft(array('pod' => 'profile_option_description'), 'po.profile_option_id = pod.profile_option_id')
						->where('pod.language_id = ?', Zend_Registry::get('language_id'));
				
		return $this->db->fetchAll($sql);
	}
	
	public function getOptionCurrentName($profile_option_id) {
		$sql = $this->db->select()
						->from(array('po' => 'profile_option'), array())
						->joinLeft(array('pod' => 'profile_option_description'), 'po.profile_option_id = pod.profile_option_id', 'name')
						->where('pod.language_id = ?', Zend_Registry::get('language_id'))
						->where('po.profile_option_id = ?', $profile_option_id);

		$res = 	$this->db->fetchRow($sql);	
		return $res['name'];
	}
	
	
	public function getValues($profile_option_id) {
		$sql = $this->db->select()
						->from(array('pov' => 'profile_option_value'))
						->joinLeft(array('povd' => 'profile_option_value_description'), 'pov.profile_option_value_id = povd.profile_option_value_id')
						->where('pov.profile_option_id = ?', $profile_option_id)
						->where('povd.language_id = ?', Zend_Registry::get('language_id'))
						->order('pov.sort_order ASC');

		
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
	
	
	public function addOption($data) {
		$this->db->insert('profile_option', array(
			'section'		=> $data['section'],
			'type'			=> $data['type'],
			'for_man'		=> $data['for_man'],
			'for_woman'		=> $data['for_woman'],
			'sort_order'	=> $data['sort_order'],
		));
		
		$option_id = $this->db->lastInsertId();

		foreach($data['language'] as $language_id => $value) {
			
			$this->db->insert('profile_option_description', array(
				'name'				=> $value['name'],
				'profile_option_id'	=> $option_id,
				'language_id'		=> $language_id,
			));
		}
	}
	
	public function editOption($data) {
		$where = 'profile_option_id = ' . (int)$data['profile_option_id'];
		$this->db->update('profile_option', array(
			'section'		=> $data['section'],
			'type'			=> $data['type'],
			'for_man'		=> $data['for_man'],
			'for_woman'		=> $data['for_woman'],
			'sort_order'	=> $data['sort_order'],
		), $where);
		
		$this->db->delete('profile_option_description', "profile_option_id = '" . (int)$data['profile_option_id'] . "'");
		foreach($data['language'] as $language_id => $value) {
			
			$this->db->insert('profile_option_description', array(
				'name'				=> $value['name'],
				'profile_option_id'	=> $data['profile_option_id'],
				'language_id'		=> $language_id,
			));
		}
	}
	
	
	public function getOption($profile_option_id) {
		
		$sql = $this->db->select()
						->from(array('op' => 'profile_option'))
						->where('op.profile_option_id = ?', $profile_option_id);

		$data = $this->db->fetchRow($sql);
		
		$sql2 = $this->db->select()
						 ->from(array('opd' => 'profile_option_description'))
						 ->where('opd.profile_option_id = ?', $profile_option_id);
		
		foreach($this->db->fetchAll($sql2) as $row) {
			$data['language'][$row['language_id']] = $row;
		}
				
		return $data;
	}
	
	public function getOptionValue($profile_option_value_id) {
		
		$sql = $this->db->select()
						->from(array('opv' => 'profile_option_value'))
						->joinLeft(array('rov' => 'related_option_values'), 'opv.profile_option_value_id = rov.value1', array('related_option_value' => 'rov.value2'))
						->where('opv.profile_option_value_id = ?', $profile_option_value_id);

		$data = $this->db->fetchRow($sql);
		
		$sql2 = $this->db->select()
						 ->from(array('opvd' => 'profile_option_value_description'))
						 ->where('opvd.profile_option_value_id = ?', $profile_option_value_id);
		
		foreach($this->db->fetchAll($sql2) as $row) {
			$data['language'][$row['language_id']] = $row;
		}
				
		return $data;
	}
	
	
	public function addOptionValue($data) {
		$this->db->insert('profile_option_value', array(
			'profile_option_id'		=> $data['profile_option_id'],
			'sort_order'			=> $data['sort_order'],
		));
		
		$option_value_id = $this->db->lastInsertId();

		foreach($data['language'] as $language_id => $value) {
			
			$this->db->insert('profile_option_value_description', array(
				'name'						=> $value['name'],
				'profile_option_value_id'	=> $option_value_id,
				'language_id'				=> $language_id,
			));
		}
	}
	
	public function editOptionValue($data) {
		$where = 'profile_option_value_id = ' . (int)$data['profile_option_value_id'];
		$this->db->update('profile_option_value', array(
			'sort_order'			=> $data['sort_order'],
		), $where);
		
		$this->db->delete('profile_option_value_description', "profile_option_value_id = '" . (int)$data['profile_option_value_id'] . "'");
		foreach($data['language'] as $language_id => $value) {
			
			$this->db->insert('profile_option_value_description', array(
				'name'						=> $value['name'],
				'profile_option_value_id'	=> $data['profile_option_value_id'],
				'language_id'				=> $language_id,
			));
		}
		
		$this->db->delete('related_option_values', "value1 = '" . (int)$data['profile_option_value_id'] . "' OR value2 = '" . (int)$data['profile_option_value_id'] . "'");
		if($data['related_option_value']) {
			$this->db->insert('related_option_values', array('value1' => $data['related_option_value'], 'value2' => $data['profile_option_value_id']));
			$this->db->insert('related_option_values', array('value2' => $data['related_option_value'], 'value1' => $data['profile_option_value_id']));
		}
		
		$value = $this->getOptionValue($data['profile_option_value_id']);
		
		return $value['profile_option_id'];
	}
	
	
	public function removeOptionValue($profile_option_value_id) {
		$value = $this->getOptionValue($profile_option_value_id);
		
		$where = "profile_option_value_id = '" . (int)$profile_option_value_id . "'";
		$this->db->delete('profile_option_value', $where);
		$this->db->delete('profile_option_value_description', $where);
		$this->db->delete('user_option_value', $where);
		
		return $value['profile_option_id'];
	}
	
	public function removeOption($profile_option_id) {
		$values = $this->getValues($profile_option_id);
		
		foreach($values as $value) {
			$this->db->delete('profile_option_value', "profile_option_value_id = '" . (int)$value['profile_option_value_id'] . "'");
			$this->db->delete('profile_option_value_description', "profile_option_value_id = '" . (int)$value['profile_option_value_id'] . "'");
		}
		
		$where = "profile_option_id = '" . (int)$profile_option_id . "'";
		$this->db->delete('profile_option', $where);
		$this->db->delete('profile_option_description', $where);
		$this->db->delete('user_option_value', $where);

	}
	
	
}