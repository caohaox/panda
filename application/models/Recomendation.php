<?php 

class Application_Model_Recomendation extends Site_Db {

	public function getRecomendations($user_id) {
		$sql = $this->db->select()
						->from(array('ur' => 'user_recomendation'));
		
		$sql->joinLeft(array('u' => 'user'), 'ur.recomended_user_id = u.user_id', array('u.*', 'fullname' => 'CONCAT(u.name_eng, " ", u.lastname_eng)'));
		

		$sql->where('ur.user_id = ?', $user_id);
			
		
		$users = $this->db->fetchAll($sql);
		
		$db_options = new Application_Model_Profileoption();
		
		$return = array();	
		foreach($users as $row) {
			unset($row['password']);

			if($row['birth_date'] != '0000-00-00') {
				$now = new Zend_Date();
				$birth = new Zend_Date($row['birth_date']);
				
				$age = $now->get(Zend_Date::YEAR) - $birth->get(Zend_Date::YEAR);
				
	         	$diffMonth = $now->get(Zend_Date::MONTH) - $birth->get(Zend_Date::MONTH);
	         	$diffDay   = $now->get(Zend_Date::DAY) - $birth->get(Zend_Date::DAY);
	         	
		        if ($diffMonth < 0){
		        	$age--;
		        }elseif	(($diffMonth == 0) && ($diffDay < 0)) {
		        	$age--;
		        }
				$row['age'] = $age;
			}else $row['age'] = false;
			
			$row['region'] = $db_options->getRegion($user_id, $row['is_man']);
			$row['sfera'] = $db_options->getSfera($user_id);
			$row['partner_age'] = $db_options->getPartnerAge($user_id);
			
			$return[] = $row;
		}
		
		return $return;
	}
	
	
	public function getTotalRecomendations($user_id) {
		$sql = $this->db->select()
						->from(array('ur' => 'user_recomendation'), array('count' => 'COUNT(*)'));
		$sql->where('ur.user_id = ?', $user_id);				
		$return = $this->db->fetchRow($sql);

		return (int)$return['count'];
	}
	
	
	
	public function delete($user_id, $recomended_user_id) {
		$recomendation = $this->db->fetchRow($this->db->select()
						->from('user_recomendation')
						->where("user_id = '" . (int)$user_id . "'")
						->where("recomended_user_id = '" . (int)$recomended_user_id . "'"));
		
		$this->db->insert('user_recomendation_archive', array(
			'recomendation_id'		=> $recomendation['recomendation_id'],
			'user_id'				=> $recomendation['user_id'],
			'recomended_user_id'	=> $recomendation['recomended_user_id'],
			'archive_added'			=> new Zend_Db_Expr('NOW()')
		));
							
		$this->db->delete('user_recomendation', "user_id = '" . (int)$user_id . "' AND recomended_user_id = '" . (int)$recomended_user_id . "'");
	}
	
	public function setNextGenerateDate() {
		$date = new Zend_Date();
		$new_date = $date->addDay((int)Site_Config::get('recomendation_period'))->toString("YYYY-MM-dd HH:mm:ss");
		$this->db->update('settings', array('value' => $new_date), "setting_name = 'next_recomendation_date'");
	}
	
	public function addAllRecomendationsToArchive() {
		$recomendations = $this->db->fetchAll($this->db->select()
						->from('user_recomendation'));
		
		foreach($recomendations as $recomendation) {
			$this->db->insert('user_recomendation_archive', array(
				'recomendation_id'		=> $recomendation['recomendation_id'],
				'user_id'				=> $recomendation['user_id'],
				'recomended_user_id'	=> $recomendation['recomended_user_id'],
				'archive_added'			=> new Zend_Db_Expr('NOW()')
			));
		}		
	}
	
	public function generate() {
		
		$this->db->delete('user_recomendation');
		
		$sql = $this->db->select()
						->from(array('u' => 'user'))
						->where('u.status = 1')
						->where('u.step1 = 1')
			  			->where('u.step2 = 1')
			  			->where('u.step3 = 1')
			  			->where('u.block = 0');
			
		$users = $this->db->fetchAll($sql);
		
		/*$sql = $this->db->select()
						->from(array('pov' => 'profile_option_value'))
						->joinLeft(array('rov' => 'related_option_values'), 'pov.profile_option_value_id = rov.value1', array('related_value_id' => 'rov.value2'));
		
		$option_values = $this->db->fetchAll($sql);*/

		$recomendation_count = Site_Config::get('recomendation_count');
			
		foreach($users as $user) {

			$archive_users = $this->db->fetchAll($this->db->select()->from('user_recomendation_archive', 'recomended_user_id')->where('user_id = ?', $user['user_id']));
			
			$imploded_users = array();
			foreach($archive_users as $archive_user) {
				$imploded_users[] = (int)$archive_user['recomended_user_id'];
			}
			
			$sql = $this->db->select()
						->from(array('uov' => 'user_option_value'))
						->joinLeft(array('rov' => 'related_option_values'), 'uov.profile_option_value_id = rov.value1', array('related_value_id' => 'rov.value2'))
						->where('uov.user_id = ?', $user['user_id']);
			
			$user_option_values = $this->db->fetchAll($sql);

			//поиск подходящих пользователей
			//для каждого желаемого пользователем параметра ищем пользователей
			//и считаем число совпадений, у кого больше совпадений записываем в рекомендуемые
			$searched_users = array();
			foreach($user_option_values as $user_option_value) {
				if($user_option_value['related_value_id']) {
					$query = $this->db->select();
					$query->from(array('u' => 'user'), array('u.user_id'));
					$query->where("SELECT COUNT(*) FROM user_option_value WHERE user_id = u.user_id AND profile_option_value_id = " . (int)$user_option_value['related_value_id'] . "");
					$query->where('u.status = 1')
						  ->where('u.step1 = 1')
						  ->where('u.step2 = 1')
						  ->where('u.step3 = 1');
					if($imploded_users) {
						$query->where("u.user_id NOT IN (" . implode(',', $imploded_users) . ")");
					}
					$query->where('u.block <> 1');
						  
					if($user['is_man']) $query->where('u.is_man = 0');
					if(!$user['is_man']) $query->where('u.is_man = 1');

					$result = $this->db->fetchAll($query);
					foreach($result as $searched_user) {
						if(isset($searched_users[$searched_user['user_id']])) {
							$searched_users[$searched_user['user_id']] = $searched_users[$searched_user['user_id']] + 1;
						}else $searched_users[$searched_user['user_id']] = 1;
					}
				}
			}
			
			arsort($searched_users);
			
			$c = 0;
			foreach($searched_users as $searched_user_id => $count) {
				if($c < $recomendation_count) {
					$this->db->insert('user_recomendation', array('user_id' => $user['user_id'], 'recomended_user_id' => $searched_user_id));
				}
				$c++;
			}
		}
	}
		
	public function generateForUser($user_id) {
		
		$this->db->delete('user_recomendation', "user_id = '" . (int)$user_id . "'");
		
		$db_user = new Application_Model_User();
		$user = $db_user->getUser($user_id);

		$recomendation_count = Site_Config::get('recomendation_count');

			$archive_users = $this->db->fetchAll($this->db->select()->from('user_recomendation_archive', 'recomended_user_id')->where('user_id = ?', $user_id));
			
			$imploded_users = array();
			foreach($archive_users as $archive_user) {
				$imploded_users[] = (int)$archive_user['recomended_user_id'];
			}
			
			$sql = $this->db->select()
						->from(array('uov' => 'user_option_value'))
						->joinLeft(array('rov' => 'related_option_values'), 'uov.profile_option_value_id = rov.value1', array('related_value_id' => 'rov.value2'))
						->where('uov.user_id = ?', $user_id);
			
			$user_option_values = $this->db->fetchAll($sql);

			//поиск подходящих пользователей
			//для каждого желаемого пользователем параметра ищем пользователей
			//и считаем число совпадений, у кого больше совпадений записываем в рекомендуемые
			$searched_users = array();
			foreach($user_option_values as $user_option_value) {
				if($user_option_value['related_value_id']) {
					$query = $this->db->select();
					$query->from(array('u' => 'user'), array('u.user_id'));
					$query->where("SELECT COUNT(*) FROM user_option_value WHERE user_id = u.user_id AND profile_option_value_id = " . (int)$user_option_value['related_value_id'] . "");
					$query->where('u.status = 1')
						  ->where('u.step1 = 1')
						  ->where('u.step2 = 1')
						  ->where('u.step3 = 1');
					if($imploded_users) {
						$query->where("u.user_id NOT IN (" . implode(',', $imploded_users) . ")");
					}
					$query->where('u.block <> 1');
						  
					if($user['is_man']) $query->where('u.is_man = 0');
					if(!$user['is_man']) $query->where('u.is_man = 1');

					$result = $this->db->fetchAll($query);
					foreach($result as $searched_user) {
						if(isset($searched_users[$searched_user['user_id']])) {
							$searched_users[$searched_user['user_id']] = $searched_users[$searched_user['user_id']] + 1;
						}else $searched_users[$searched_user['user_id']] = 1;
					}
				}
			}
			
			arsort($searched_users);
			
			$c = 0;
			foreach($searched_users as $searched_user_id => $count) {
				if($c < $recomendation_count) {
					$this->db->insert('user_recomendation', array('user_id' => $user_id, 'recomended_user_id' => $searched_user_id));
				}
				$c++;
			}
		
	}
}