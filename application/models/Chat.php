<?php 

class Application_Model_Chat extends Site_Db {

	public function getUnreadMessagesByUsers($user_id) {

		$sql = $this->db->select()
						->from(array('m' => 'message'), array('count' => 'COUNT(*)', 'm.from_user_id', 'date_added' => 'MAX(date_added)'));

		//$sql->joinLeft(array('u' => 'user'), 'm.from_user_id = u.user_id', array('u.user_id', 'image', 'fullname' => 'CONCAT(u.name_eng, " ", u.lastname_eng)'));		
		$sql->where('m.to_user_id = ?', $user_id)
			->where('m.read_date IS NULL')
			->group('m.from_user_id');
		
		/*$return = array();				
		foreach($this->db->fetchAll($sql) as $row) {
			if(((Zend_Registry::get('language_id') == 1) && !$row['is_man']) || ((Zend_Registry::get('language_id') == 3) && $row['is_man'])) {
				$return[] = array(
					'user_id'	=> $row['user_id'],
					'date_added'=> $row['date_added'],
					'count'		=> $row['count'],
					'image'		=> $row['image'],
					'fullname'	=> $row['fullname'],
				);
			}else {
				$return[] = array(
					'user_id'	=> $row['user_id'],
					'date_added'=> $row['date_added'],
					'count'		=> $row['count'],
					'image'		=> $row['image'],
					'fullname'	=> $row['fullname_eng'],
				);
			}
		}*/
							
		return $this->db->fetchAll($sql);
		
	}
	
	public function getUnreadMessagesByUser($user_id, $partner_id) {
		
		$sql = $this->db->select()
						->from(array('m' => 'message'), array('m.*', 'date' => 'DATE_FORMAT(date_added, "%H:%i:%s")'))
						->where('m.to_user_id = ?', $user_id)
						->where('m.from_user_id = ?', $partner_id)
						->where('m.read_date IS NULL')
						->order('m.date_added ASC');
		
		$result = array();
		foreach($this->db->fetchAll($sql) as $row) {
			$row['description'] = $this->replaceSmiles($row['description']);
			$result[] = $row;				
		}
		return $result;
		
	}
	
	
	public function getAllChats($user_id) {
		
		
		//сообщения ОТ пользователя
		$sql2 = $this->db->select()
						->distinct()
						->from(array('m' => 'message'), array())
						->joinLeft(array('u' => 'user'), 'm.to_user_id = u.user_id', array('user_id' => 'u.user_id', 'image', 'fullname' => 'CONCAT(u.name_eng, " ", u.lastname_eng)'))		
						->where('m.from_user_id = ?', $user_id)
						->order('m.date_added DESC')
						->group('m.to_user_id');
		
			
		//сообщения ДЛЯ пользователя
		$sql = $this->db->select()
						->distinct()
						->from(array('m' => 'message'), array())
						->joinLeft(array('u' => 'user'), 'm.from_user_id = u.user_id', array('user_id' => 'u.user_id', 'image', 'fullname' => 'CONCAT(u.name_eng, " ", u.lastname_eng)'))
						->where('m.to_user_id = ?', $user_id)
						->order('m.date_added DESC')
						->group('m.from_user_id');
					
		$return = array();				
		foreach($this->db->fetchAll($sql) as $row) {
			$return[$row['user_id']] = $row;
		}
			
		foreach($this->db->fetchAll($sql2) as $row) {
			if(!isset($return[$row['user_id']])) {
				$return[$row['user_id']] = $row;
			}
		}
							
		return $return;
		
	}
	

	public function getTotalUnreadMessages($user_id) {
		
		$sql = $this->db->select()
						->from(array('m' => 'message'), array('count' => 'COUNT(*)'))
						->where('m.to_user_id = ?', $user_id)
						->where('m.read_date IS NULL');

		
		$return = $this->db->fetchRow($sql);
		
							
		return (int)$return['count'];
	}
	
	public function getMessages($user_from, $user_to, $limit = 10) {
		$sql = $this->db->select()
						->from('message', array('*', 'date' => 'DATE_FORMAT(date_added, "%H:%i:%s")'))
						->where("to_user_id = '" . (int)$user_to . "' AND from_user_id = '" . (int)$user_from . "'")
						->orWhere("to_user_id = '" . (int)$user_from . "' AND from_user_id = '" . (int)$user_to . "'")
						->order('date_added DESC')
						->limit($limit);
		
		$return = array();
		foreach($this->db->fetchAll($sql) as $row) {
			$row['description'] = $this->replaceSmiles($row['description']);
			$return[] = $row;
		}
		return array_reverse($return);
	}
	

	
	public function addMessage($user_from, $user_to, $message) {
		$filter = new Zend_Filter_StripTags();
		$message = $filter->filter($message);
		$this->db->insert('message', array(
			'from_user_id' => $user_from, 
			'to_user_id' => $user_to, 
			'description' => $message, 
			'date_added' => new Zend_Db_Expr('NOW()')));
		
		//возвращаем дату
		$message_id = $this->db->lastInsertId();
		$sql = $this->db->select()
						->from('message', array('date' => 'DATE_FORMAT(date_added, "%H:%i:%s")'))
						->where('message_id = ?', $message_id);
						
		$result = $this->db->fetchRow($sql);
		return array('date' => $result['date'], 'message' => $this->replaceSmiles($message), 'message_id' => $message_id);
		
	}


	
	public function setRead($message_id) {
		$this->db->update('message', array('read_date' => new Zend_Db_Expr('NOW()')), "message_id = '" . (int)$message_id . "'");
	}
	
	public function checkMessage($message_id, $user_id) {
		if(is_array($message_id)) {
			foreach($message_id as $m_id) {
				$sql = $this->db->select()
							->from('message', 'message_id')
							->where('to_user_id = ?', $user_id)
							->where('message_id = ?', $m_id);
							
				if(!count($this->db->fetchAll($sql))) {
					return false;
				}
			}
		}else {
			$sql = $this->db->select()
							->from('message', 'message_id')
							->where('to_user_id = ?', $user_id)
							->where('message_id = ?', $message_id);
		
			if(!count($this->db->fetchAll($sql))) {
				return false;
			}
		}
		return true;
	}
	
	private function replaceSmiles($text) {
		
		$smiles = array(
			':)',
			':o',
			':D',
			';)',
			':p',
			':cool:',
			':rolleyes:',
			':mad:',
			':eek:',
			':confused:',
			':agree:',
			':angry:',
			':blink:',
			':cray:',
			':dance:',
			':haha:',
			':help:',
			':kissed:',
			':laugh:',
			':zzz:',
			':lol:',
			':mamba:',
			':no:',
			':nono:',
			':offtopic:',
			':(',
			':secret:',
			':stop:',
			':thanks:',
			':unsure:',
			':victory:',
			':wacko:',
			':yes:',
		);
		
		$view = Zend_Registry::get('view');
		$baseUrl = $view->baseUrl('');
		
		$images = array(
			'<img src="' . $baseUrl . 'img/smiles/1.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/2.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/3.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/4.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/5.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/6.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/7.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/8.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/9.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/10.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/11.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/12.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/13.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/14.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/15.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/16.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/17.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/18.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/19.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/20.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/21.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/22.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/23.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/24.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/25.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/26.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/27.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/28.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/29.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/30.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/31.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/32.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/33.gif" />',
		);
		
		
		return str_replace($smiles, $images, $text);
	}
	
	
	public function spamReport($message_id, $type, $text) {
		$this->db->insert('spam', array('message_id' => $message_id, 'type' => $type, 'text' => $text, 'date_added' => new Zend_Db_Expr('NOW()')));
	}
}