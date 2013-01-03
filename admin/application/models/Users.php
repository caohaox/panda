<?php
class Application_Model_Users extends Admin_Db {
	
	/**
	 * used by Zend_Pagination
	 * @return Zend_Db_Select object
	 */
	public function getUsers($sort = 'user_id', $order = 'DESC') {
		
		$query = $this->db->select();
		
		if(Zend_Registry::get('language_id') != 1) {
			$query->from('user', array('*', 'name' => 'name_eng', 'lastname' => 'lastname_eng'));
		}else $query->from('user');
						 
		$query->order($sort . ' ' . $order);
						  
		return $query;
	}
	
	
	/**
	 * used by Zend_Pagination
	 * @return Zend_Db_Select object
	 */
	public function getTotalUsers() {
		$query = $this->db->select()
						  ->from('user', array(Zend_Paginator_Adapter_DbSelect::ROW_COUNT_COLUMN => 'COUNT(*)'));
						  
		return $query;
	}
	
	
	public function blockUser($user_id) {
		
		$this->db->update('user', array('block' => 1), 'user_id = ' . (int)$user_id);
	}
	
	
	public function unblockUser($user_id) {
		
		$this->db->update('user', array('block' => 0), 'user_id = ' . (int)$user_id);
	}
	
	
	public function getUser($user_id) {
		$query = $this->db->select()
						  ->from('user', array('*', 'fullname' => 'CONCAT(name_eng, " ", lastname_eng)'))
						  ->where('user_id = ?', $user_id);
						  
		return $this->db->fetchRow($query);
	}
	
	
	/*public function editUser($data) {
		$where = 'user_id = ' . (int)$data['user_id'];
		$this->db->update('user', $data, $where);
	}*/
	
	public function editUser($user_id, $data) {
		
		if((int)$data['step1']['height'] < 1) $data['step1']['height'] = '';
		//if((int)$data['step1']['weight'] < 1) $data['step1']['weight'] = '';
		
		$this->db->update('user', array(
			'email' 		=> $data['step1']['email'], 
			'telephone' 	=> $data['step1']['telephone'], 
			'skype' 		=> $data['step1']['skype'], 
			'city'			=> $data['step1']['city'], 
			//'home'			=> $data['step1']['home'], 
			'height'		=> $data['step1']['height'], 
			//'weight'		=> $data['step1']['weight'], 
			'birth_date' 	=> $data['step1']['year'] . '-' . (($data['step1']['month'] < 10)?'0' . $data['step1']['month']:$data['step1']['month']) . '-' . (($data['step1']['day'] < 10)?'0' . $data['step1']['day']:$data['step1']['day'])
		), "user_id = '" . (int)$user_id . "'");
		
		$this->updateProfileOptions($user_id, array_merge($data['step1'], $data['step2'], $data['step3']));
		
		$this->setLanguages($user_id, $data['step1']['languages']);
	}
	
	
	private function updateProfileOptions($user_id, $data) {
		
		$this->db->delete('user_option_value', "user_id = '" . (int)$user_id . "'");
		
		foreach($data as $optionKey => $option_value_id) {
			if(strpos($optionKey, 'profile_select') !== false) {
				$option = explode('profile_select', $optionKey);
				if(isset($option[1])) {
					//$this->db->delete('user_option_value', "user_id = '" . (int)$user_id . "' AND profile_option_id = '" . (int)$option[1] . "'");
					if(!empty($option_value_id)) {
						$this->db->insert('user_option_value', array('user_id' => $user_id, 'profile_option_id' => $option[1], 'profile_option_value_id' => $option_value_id));
					}
				}
			}
		}
		
		foreach($data as $optionKey => $option_values) {
			if(strpos($optionKey, 'profile_checkbox') !== false) {
				$option = explode('profile_checkbox', $optionKey);
				if(isset($option[1])) {
					//$this->db->delete('user_option_value', "user_id = '" . (int)$user_id . "' AND profile_option_id = '" . (int)$option[1] . "'");
					if(!empty($option_values)) {
						foreach($option_values as $option_value_id) {
							$this->db->insert('user_option_value', array('user_id' => $user_id, 'profile_option_id' => $option[1], 'profile_option_value_id' => $option_value_id));
						}
					}
				}
			}
		}
	}
	

	
	
	private function setLanguages($user_id, $languages) {
		$this->db->delete('user_to_language', "user_id = '" . (int)$user_id . "'");
		if(!empty($languages)) {
			$insert = array();
			foreach($languages as $key => $value) {
				if(strpos($key, 'language') !== false) {
					$count = explode('language', $key);
					if(isset($count[1])) {
						$insert[$count[1]]['language'] = $value;
					}
				}elseif(strpos($key, 'skill') !== false) {
					$count = explode('skill', $key);
					if(isset($count[1])) {
						$insert[$count[1]]['skill'] = $value;
					}
				}
			}

			foreach($insert as $in) {
				if(!empty($in['language']) && !empty($in['skill'])) {
					//$this->db->replace('user_to_language', array('user_id' => $user_id, 'all_language_id' => $in['language'], 'skill_id' => $in['skill']));
					$this->db->query("REPLACE INTO user_to_language SET user_id = '" . (int)$user_id . "', all_language_id = '" . (int)$in['language'] . "', skill_id = '" . (int)$in['skill'] . "'");
				}
			}
		}
	}
	
	
	
	public function findUser($name) {
		$query = $this->db->select()
						  ->from('user', array('*', 'fullname' => 'CONCAT(name_eng, " ", lastname_eng)'))
						  ->where("CONCAT(LOWER(name), ' ', LOWER(lastname), ' ', LOWER(name_eng), ' ', LOWER(lastname_eng)) LIKE " . $this->db->quote('%' . mb_strtolower($name, 'UTF-8') . '%'));
						  
		return $this->db->fetchAll($query);
	}
	
	
	public function getLanguagesByUserId($user_id) {
		$sql = $this->db->select()
						->from(array('u2l' => 'user_to_language'))
						->joinLeft(array('aln' => 'all_languages_names'), 'u2l.all_language_id = aln.all_language_id', array('language_name' => 'aln.name'))
						->joinLeft(array('sn' => 'skills_names'), 'u2l.skill_id = sn.skill_id', array('skill_name' => 'sn.name'))
						->where('sn.language_id = ?', Zend_Registry::get('language_id'))
						->where('aln.language_id = ?', Zend_Registry::get('language_id'))
						->where('u2l.user_id = ?', $user_id);
						
		return $this->db->fetchAll($sql);
	}
	
	
	public function getUserForEdit($user_id) {
		$query = $this->db->select();
		
		
		$query->from(array('u' => 'user'), array('u.*', 'fullname' => 'CONCAT(u.name, " ", u.lastname)', 'fullname_eng' => 'CONCAT(u.name_eng, " ", u.lastname_eng)'));
		
		
		$query->where('u.user_id = ?', $user_id);

		$return = $this->db->fetchRow($query);
		
		if($return) {
			if($return['birth_date'] != '0000-00-00') {
				$now = new Zend_Date();
				$birth = new Zend_Date($return['birth_date']);
				
				$age  = $now->get(Zend_Date::YEAR) - $birth->get(Zend_Date::YEAR);
				
	         	$diffMonth = $now->get(Zend_Date::MONTH) - $birth->get(Zend_Date::MONTH);
	         	$diffDay   = $now->get(Zend_Date::DAY) - $birth->get(Zend_Date::DAY);
	         	
		        if ($diffMonth < 0){
		        	$age--;
		        }elseif	(($diffMonth == 0) && ($diffDay < 0)) {
		        	$age--;
		        }
				$return['age'] = $age;
			}else $return['age'] = false;
		}
		
		$db_options = new Application_Model_Profileoption();
		
		//$return['region'] = $db_options->getRegion($user_id, $return['is_man']);
		//$return['partner_age'] = $db_options->getPartnerAge($user_id);
		
		unset($return['password']);
		return $return;
	}
}