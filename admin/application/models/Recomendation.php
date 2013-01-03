<?php 

class Application_Model_Recomendation extends Admin_Db {

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
			
			//$row['region'] = $db_options->getRegion($user_id, $row['is_man']);
			//$row['sfera'] = $db_options->getSfera($user_id);
			//$row['partner_age'] = $db_options->getPartnerAge($user_id);
			
			$return[] = $row;
		}
		
		return $return;
	}
	
	
	public function removeRecomendation($recomendation_id) {
		$this->db->delete('user_recomendation', "recomendation_id = '" . (int)$recomendation_id . "'");
	}
	
	public function addRecomendation($user_id, $recomended_user_id) {
		$this->db->insert('user_recomendation', array('user_id' => $user_id, 'recomended_user_id' => $recomended_user_id));
	}
}